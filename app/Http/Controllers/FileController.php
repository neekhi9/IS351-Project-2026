<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\WiremanLicense;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Serve registration files with proper authorization
     */
    public function serveRegistrationFile(Request $request, $registrationId, $fileType)
    {
        $registration = Registration::findOrFail($registrationId);
        
        // Check authorization
        if (!$this->canAccessRegistrationFile($registration)) {
            abort(403, 'Unauthorized access to file');
        }

        $filePath = $this->getRegistrationFilePath($registration, $fileType);
        
        if (!$filePath) {
            abort(404, 'File not found');
        }

        return $this->serveFile($filePath);
    }

    /**
     * Serve wireman license files with proper authorization
     */
    public function serveWiremanLicenseFile(Request $request, $licenseId)
    {
        $license = WiremanLicense::findOrFail($licenseId);
        $registration = $license->registration;
        
        // Check authorization
        if (!$this->canAccessRegistrationFile($registration)) {
            abort(403, 'Unauthorized access to file');
        }

        if (!$license->license_file_path) {
            abort(404, 'File not found');
        }

        return $this->serveFile($license->license_file_path);
    }

    /**
     * Check if current user can access registration files
     */
    private function canAccessRegistrationFile(Registration $registration): bool
    {
        $user = auth()->user();
        
        // Admin can access all files
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // Users can only access their own registration files
        return $user->email === $registration->email;
    }

    /**
     * Get file path for registration file type
     */
    private function getRegistrationFilePath(Registration $registration, string $fileType): ?string
    {
        return match($fileType) {
            'roc_certificate' => $registration->roc_file_path,
            'tin_letter' => $registration->tin_letter_path,
            'wireman_license_ind' => $registration->wireman_license_ind_path,
            default => null,
        };
    }

    /**
     * Serve file with proper headers
     */
    private function serveFile(string $filePath): BinaryFileResponse
    {
        // Convert URL path to storage path
        $storagePath = str_replace('/storage/', '', $filePath);
        $fullPath = storage_path('app/public/' . $storagePath);
        
        if (!file_exists($fullPath)) {
            abort(404, 'File not found on disk');
        }

        return response()->file($fullPath, [
            'Content-Type' => $this->getMimeType($fullPath),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Get MIME type for file
     */
    private function getMimeType(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        return match($extension) {
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };
    }
}
