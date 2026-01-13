<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            "Attribute Add", "Attribute Delete", "Attribute Status Change", "Attribute Update",
            "Attribute Value Add", "Attribute Value Delete", "Attribute Value Update", "Attribute View",
            "Banner Add", "Banner Delete", "Banner Status Change", "Banner Update", "Banner View", "Brand Add",
            "Brand Delete", "Brand Status Change", "Brand Update", "Brand View", "Category Add",
            "Category Delete", "Category Include to Home", "Category Status Change", "Category Update",
            "Category View", "Color Add", "Color Delete", "Color Update", "Color View", "Country Add",
            "Country Delete", "Country Update", "Country View", "Email Template Add", "Email Template Delete",
            "Email Template Title Edit", "Email Template Update", "Email Template View", "Frontend-Page Add",
            "Frontend-Page Delete", "Frontend-Page Update", "Frontend-Page View", "Message Delete", "Message View",
            "Page Create", "Page Delete", "Page Update", "Page View", "Permission Add", "Permission Delete",
            "Permission Status Change", "Permission Update", "Permission View", "Role Add", "Role Assignment",
            "Role Delete", "Role Permission Add/Update", "Role Update", "Role View", "Settings Activation",
            "Settings Add", "Settings Contact", "Settings Language", "Settings Logo & Favicon", "Settings Site",
            "Settings Social Media", "Settings Store", "Settings Update", "Settings View", "Slider Add",
            "Slider Delete", "Slider Status Change", "Slider Update", "Slider View", "Sms Template Add",
            "Sms Template Delete", "Sms Template Title Edit", "Sms Template Update", "Sms Template View",
            "Staff Create", "Staff Delete", "Staff Status Change", "Staff Update", "Staff View",
            "Subscriber View", "Supplier Add",
            "Supplier Delete", "Supplier Status Change", "Supplier Update", "Supplier View",
            "Unit Add", "Unit Delete", "Unit Status Change", "Unit Update", "Unit View"     ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
