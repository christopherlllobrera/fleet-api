<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[Fillable([
    'user_id',
    'employee_no',
    'first_name',
    'middle_name',
    'last_name',
    'email',
    'company_id',
    'department_id',
    'position_id',
    'date_hired',
    'regularization_date',
    'is_active',
    'data_privacy_consent',
    'remarks',
    'status',
])]

class Employee extends Model
{
    protected function casts(): array
    {
        return [
            'date_hired' => 'date',
            'regularization_date' => 'date',
            'is_active' => 'boolean',
            'data_privacy_consent' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(EmployeeContact::class);
    }

    public function governmentInfo(): HasOne
    {
        return $this->hasOne(EmployeeGovernmentInfo::class);
    }

    public function insurance(): HasOne
    {
        return $this->hasOne(EmployeeInsurance::class);
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmployeeEmergencyContact::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(EmployeeAddress::class);
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(EmployeeDependent::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(EmployeeEducation::class);
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(EmployeeCertification::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(EmployeeAttachment::class);
    }

    public function info(): HasOne
    {
        return $this->hasOne(EmployeeInfo::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function employeeprofiles(): HasMany
    {
        return $this->hasMany(EmployeeProfile::class);
    }

}
