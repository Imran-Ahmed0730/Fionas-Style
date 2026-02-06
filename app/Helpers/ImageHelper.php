<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Convert image to base64 format, handling webp to jpg conversion
     * Returns data URL suitable for embedding in HTML/PDF
     */
    public static function getLogoAsBase64()
    {
        $logoPath = getSetting('site_logo');
        if (!$logoPath) {
            return null;
        }

        return self::convertToBase64($logoPath);
    }

    /**
     * Convert image file to base64 data URL
     * If webp, converts to jpg first using GD library
     */
    public static function convertToBase64($imagePath)
    {
        if (!$imagePath) {
            return null;
        }

        $fullPath = base_path('public/' . $imagePath);
        if (!file_exists($fullPath)) {
            return null;
        }

        try {
            $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

            if ($ext === 'webp') {
                // Convert WebP to JPG using GD library
                $img = imagecreatefromwebp($fullPath);
                if ($img) {
                    ob_start();
                    imagejpeg($img, null, 90);
                    $imageData = ob_get_clean();
                    imagedestroy($img);
                    $base64 = base64_encode($imageData);
                    return 'data:image/jpeg;base64,' . $base64;
                } else {
                    // Fallback if GD fails
                    $imageData = file_get_contents($fullPath);
                    $base64 = base64_encode($imageData);
                    return 'data:image/webp;base64,' . $base64;
                }
            } else {
                // For other formats, encode directly
                $imageData = file_get_contents($fullPath);
                $base64 = base64_encode($imageData);
                $mimeType = self::getMimeType($ext);
                return 'data:' . $mimeType . ';base64,' . $base64;
            }
        } catch (\Exception $e) {
            \Log::error('Image to Base64 conversion error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get MIME type by file extension
     */
    private static function getMimeType($ext)
    {
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
        ];

        return $mimeTypes[$ext] ?? 'image/jpeg';
    }
}
