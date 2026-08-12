# Data dictionary

## activity_log

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| log_name | varchar(255) | yes |  |  |
| description | text | no |  |  |
| subject_type | varchar(255) | yes |  |  |
| subject_id | bigint unsigned | yes |  |  |
| event | varchar(255) | yes |  |  |
| causer_type | varchar(255) | yes |  |  |
| causer_id | bigint unsigned | yes |  |  |
| attribute_changes | json | yes |  |  |
| properties | json | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: activity_log_log_name_index (log_name); causer (causer_type, causer_id); subject (subject_type, subject_id)

## bachelor_degrees

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| bachelor_name | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: bachelor_degrees_bachelor_name_unique (bachelor_name) UNIQUE

## barangays

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| municipality_id | bigint unsigned | no |  | FK |
| barangay_name | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: barangays_municipality_id_foreign (municipality_id)

Foreign keys: municipality_id -> municipalities.id (on delete: cascade, on update: no action)

## business_units

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| company_id | bigint unsigned | yes |  | FK |
| name | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: business_units_company_id_foreign (company_id)

Foreign keys: company_id -> companies.id (on delete: cascade, on update: no action)

## charge_accounts

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| name | varchar(255) | yes |  |  |
| business_unit_id | bigint unsigned | yes |  | FK |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: charge_accounts_business_unit_id_foreign (business_unit_id)

Foreign keys: business_unit_id -> business_units.id (on delete: cascade, on update: no action)

## companies

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| name | varchar(255) | no |  |  |
| code | varchar(255) | yes |  |  |
| description | text | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: companies_name_unique (name) UNIQUE

## corrective_work_order

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| job_order_no | varchar(255) | yes |  |  |
| job_order_sap_no | varchar(255) | yes |  |  |
| billing_invoice_no | varchar(255) | yes |  |  |
| charge_account_no | varchar(255) | yes |  |  |
| plate_no_id | bigint unsigned | no |  | FK |
| vehicle_location | varchar(255) | yes |  |  |
| odometer_reading | varchar(255) | yes |  |  |
| requisition_office | varchar(255) | yes |  |  |
| vehicle_trouble_report | longtext | yes |  |  |
| initial_assessment | longtext | yes |  |  |
| actual_work_time | json | yes |  |  |
| issuance_of_materials | json | yes |  |  |
| return_of_materials | json | yes |  |  |
| vehicle_date_released | json | yes |  |  |
| status | varchar(255) | yes |  |  |
| driver_name_id | bigint unsigned | no |  | FK |
| contact_person_id | bigint unsigned | no |  | FK |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |
| type | varchar(255) | yes |  |  |
| assignment | varchar(255) | yes |  |  |
| UCR_ref_no | varchar(255) | yes |  |  |
| UCR_amount | varchar(255) | yes |  |  |
| invoice | varchar(255) | yes |  |  |
| file_attachment | longtext | yes |  |  |

Indexes: corrective_work_order_billing_invoice_no_unique (billing_invoice_no) UNIQUE; corrective_work_order_contact_person_id_foreign (contact_person_id); corrective_work_order_driver_name_id_foreign (driver_name_id); corrective_work_order_job_order_no_unique (job_order_no) UNIQUE; corrective_work_order_job_order_sap_no_unique (job_order_sap_no) UNIQUE; corrective_work_order_plate_no_id_foreign (plate_no_id)

Foreign keys: contact_person_id -> employees.id (on delete: cascade, on update: no action); driver_name_id -> employees.id (on delete: cascade, on update: no action); plate_no_id -> vehicles.id (on delete: no action, on update: no action)

## countries

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| country_name | varchar(255) | no |  |  |
| phone_directory | varchar(255) | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: countries_country_name_unique (country_name) UNIQUE

## departments

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| department_no | varchar(255) | no |  |  |
| department_description | text | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: departments_department_no_unique (department_no) UNIQUE

## dispatches

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| ticket_no | varchar(255) | no |  |  |
| request_item | varchar(255) | no |  |  |
| passenger_count | int | yes |  |  |
| vehicle_id | bigint unsigned | no |  | FK |
| driver_id | bigint unsigned | no |  | FK |
| requesting_office_id | bigint unsigned | no |  | FK |
| from_location | text | yes |  |  |
| from_lat | decimal(10,7) | yes |  |  |
| from_lng | decimal(10,7) | yes |  |  |
| to_location | text | yes |  |  |
| to_lat | decimal(10,7) | yes |  |  |
| to_lng | decimal(10,7) | yes |  |  |
| purpose | varchar(255) | yes |  |  |
| priority_level | varchar(255) | yes |  |  |
| departure_time | datetime | yes |  |  |
| en_route_time | datetime | yes |  |  |
| complete_time | datetime | yes |  |  |
| cancel_time | datetime | yes |  |  |
| reason | varchar(255) | yes |  |  |
| status | varchar(255) | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: dispatches_driver_id_foreign (driver_id); dispatches_requesting_office_id_foreign (requesting_office_id); dispatches_vehicle_id_foreign (vehicle_id)

Foreign keys: driver_id -> drivers.id (on delete: cascade, on update: no action); requesting_office_id -> requesting_offices.id (on delete: cascade, on update: no action); vehicle_id -> vehicles.id (on delete: cascade, on update: no action)

## doctorate_degrees

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| doctorate_name | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: doctorate_degrees_doctorate_name_unique (doctorate_name) UNIQUE

## drivers

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| employee_id | bigint unsigned | no |  | FK |
| license_no | varchar(255) | no |  |  |
| license_expiry | date | no |  |  |
| license_class | varchar(255) | no |  |  |
| medical_expiry | date | no |  |  |
| country_id | bigint unsigned | no |  | FK |
| status | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: drivers_country_id_foreign (country_id); drivers_employee_id_foreign (employee_id)

Foreign keys: country_id -> countries.id (on delete: cascade, on update: no action); employee_id -> employees.id (on delete: cascade, on update: no action)

## employee_addresses

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| employee_id | bigint unsigned | no |  | FK |
| type | enum('present','permanent') | no |  |  |
| country_id | bigint unsigned | yes |  | FK |
| region_id | bigint unsigned | yes |  | FK |
| province_id | bigint unsigned | yes |  | FK |
| municipality_id | bigint unsigned | yes |  | FK |
| barangay_id | bigint unsigned | yes |  | FK |
| address | text | no |  |  |
| is_same_as_permanent | tinyint(1) | yes | 0 |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: employee_addresses_barangay_id_foreign (barangay_id); employee_addresses_country_id_foreign (country_id); employee_addresses_employee_id_type_unique (employee_id, type) UNIQUE; employee_addresses_municipality_id_foreign (municipality_id); employee_addresses_province_id_foreign (province_id); employee_addresses_region_id_foreign (region_id)

Foreign keys: barangay_id -> barangays.id (on delete: set null, on update: no action); country_id -> countries.id (on delete: set null, on update: no action); employee_id -> employees.id (on delete: cascade, on update: no action); municipality_id -> municipalities.id (on delete: set null, on update: no action); province_id -> provinces.id (on delete: set null, on update: no action); region_id -> regions.id (on delete: set null, on update: no action)

## employee_attachments

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| employee_id | bigint unsigned | no |  | FK |
| type | enum('birth_certificate','marital','death_certificate','separation','educational','license','other') | no |  |  |
| file_path | varchar(255) | no |  |  |
| description | text | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: employee_attachments_employee_id_foreign (employee_id)

Foreign keys: employee_id -> employees.id (on delete: cascade, on update: no action)

## employee_certifications

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| employee_id | bigint unsigned | no |  | FK |
| institution | varchar(255) | no |  |  |
| license | varchar(255) | yes |  |  |
| license_number | varchar(255) | yes |  |  |
| date_issued | date | yes |  |  |
| date_expiry | date | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: employee_certifications_employee_id_foreign (employee_id)

Foreign keys: employee_id -> employees.id (on delete: cascade, on update: no action)

## employee_contacts

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| employee_id | bigint unsigned | no |  | FK |
| type | enum('mobile','home','work') | no |  |  |
| value | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: employee_contacts_employee_id_foreign (employee_id)

Foreign keys: employee_id -> employees.id (on delete: cascade, on update: no action)

## employee_dependents

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| employee_id | bigint unsigned | no |  | FK |
| full_name | varchar(255) | no |  |  |
| date_of_birth | date | yes |  |  |
| relationship | enum('spouse','child','parent','sibling','other') | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: employee_dependents_employee_id_foreign (employee_id)

Foreign keys: employee_id -> employees.id (on delete: cascade, on update: no action)

## employee_educations

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| employee_id | bigint unsigned | no |  | FK |
| degree_type | varchar(255) | no |  |  |
| degree_name | varchar(255) | no |  |  |
| school_id | bigint unsigned | yes |  | FK |
| start_date | date | yes |  |  |
| end_date | date | yes |  |  |
| duration_of_course | varchar(255) | yes |  |  |
| final_grade | varchar(255) | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: employee_educations_employee_id_foreign (employee_id); employee_educations_school_id_foreign (school_id)

Foreign keys: employee_id -> employees.id (on delete: cascade, on update: no action); school_id -> schools.id (on delete: set null, on update: no action)

## employee_emergency_contacts

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| employee_id | bigint unsigned | no |  | FK |
| name | varchar(255) | no |  |  |
| relationship | varchar(255) | no |  |  |
| contact_no | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: employee_emergency_contacts_employee_id_foreign (employee_id)

Foreign keys: employee_id -> employees.id (on delete: cascade, on update: no action)

## employee_government_infos

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| employee_id | bigint unsigned | no |  | FK |
| tin_no | varchar(255) | yes |  |  |
| sss_no | varchar(255) | yes |  |  |
| pag_ibig_no | varchar(255) | yes |  |  |
| philhealth_no | varchar(255) | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: employee_government_infos_employee_id_foreign (employee_id)

Foreign keys: employee_id -> employees.id (on delete: cascade, on update: no action)

## employee_infos

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| employee_id | bigint unsigned | no |  | FK |
| data_privacy_consent | tinyint(1) | no | 0 |  |
| remarks | text | yes |  |  |
| status | varchar(255) | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: employee_infos_employee_id_foreign (employee_id)

Foreign keys: employee_id -> employees.id (on delete: cascade, on update: no action)

## employee_insurances

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| employee_id | bigint unsigned | no |  | FK |
| provider | varchar(255) | yes |  |  |
| med_card_no | varchar(255) | yes |  |  |
| med_card_policy_no | varchar(255) | yes |  |  |
| valid_until | date | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: employee_insurances_employee_id_foreign (employee_id)

Foreign keys: employee_id -> employees.id (on delete: cascade, on update: no action)

## employee_profiles

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| employee_id | bigint unsigned | no |  | FK |
| date_of_birth | date | yes |  |  |
| gender | varchar(255) | yes |  |  |
| suffix_name | varchar(255) | yes |  |  |
| place_of_birth | varchar(255) | yes |  |  |
| civil_status | varchar(255) | yes |  |  |
| date_of_marriage | date | yes |  |  |
| spouse_name | varchar(255) | yes |  |  |
| spouse_date_of_birth | date | yes |  |  |
| spouse_place_of_birth | varchar(255) | yes |  |  |
| mother_name | varchar(255) | yes |  |  |
| mother_date_of_birth | date | yes |  |  |
| father_name | varchar(255) | yes |  |  |
| father_date_of_birth | date | yes |  |  |
| date_of_death | date | yes |  |  |
| date_of_separation | date | yes |  |  |
| address | text | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |
| nationality_id | bigint unsigned | yes |  | FK |
| personal_number | varchar(255) | yes |  |  |

Indexes: employee_profiles_employee_id_foreign (employee_id); employee_profiles_nationality_id_foreign (nationality_id); employee_profiles_personal_number_unique (personal_number) UNIQUE

Foreign keys: employee_id -> employees.id (on delete: cascade, on update: no action); nationality_id -> nationalities.id (on delete: set null, on update: no action)

## employees

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| user_id | bigint unsigned | yes |  | FK |
| employee_no | varchar(255) | no |  |  |
| first_name | varchar(255) | no |  |  |
| middle_name | varchar(255) | yes |  |  |
| last_name | varchar(255) | no |  |  |
| email | varchar(255) | no |  |  |
| company_id | bigint unsigned | yes |  | FK |
| department_id | bigint unsigned | yes |  | FK |
| position_id | bigint unsigned | yes |  | FK |
| date_hired | date | no |  |  |
| regularization_date | date | yes |  |  |
| is_active | tinyint(1) | no | 1 |  |
| data_privacy_consent | tinyint(1) | no | 0 |  |
| remarks | text | yes |  |  |
| status | varchar(255) | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: employees_company_id_foreign (company_id); employees_department_id_foreign (department_id); employees_email_unique (email) UNIQUE; employees_employee_no_unique (employee_no) UNIQUE; employees_position_id_foreign (position_id); employees_user_id_foreign (user_id)

Foreign keys: company_id -> companies.id (on delete: set null, on update: no action); department_id -> departments.id (on delete: set null, on update: no action); position_id -> positions.id (on delete: set null, on update: no action); user_id -> users.id (on delete: cascade, on update: no action)

## exports

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| completed_at | timestamp | yes |  |  |
| file_disk | varchar(255) | no |  |  |
| file_name | varchar(255) | yes |  |  |
| exporter | varchar(255) | no |  |  |
| processed_rows | int unsigned | no | 0 |  |
| total_rows | int unsigned | no |  |  |
| successful_rows | int unsigned | no | 0 |  |
| user_id | bigint unsigned | no |  | FK |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: exports_user_id_foreign (user_id)

Foreign keys: user_id -> users.id (on delete: cascade, on update: no action)

## failed_import_rows

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| data | json | no |  |  |
| import_id | bigint unsigned | no |  | FK |
| validation_error | text | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: failed_import_rows_import_id_foreign (import_id)

Foreign keys: import_id -> imports.id (on delete: cascade, on update: no action)

## imports

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| completed_at | timestamp | yes |  |  |
| file_name | varchar(255) | no |  |  |
| file_path | varchar(255) | no |  |  |
| importer | varchar(255) | no |  |  |
| processed_rows | int unsigned | no | 0 |  |
| total_rows | int unsigned | no |  |  |
| successful_rows | int unsigned | no | 0 |  |
| user_id | bigint unsigned | no |  | FK |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: imports_user_id_foreign (user_id)

Foreign keys: user_id -> users.id (on delete: cascade, on update: no action)

## incidents

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| company_id | bigint unsigned | no |  | FK |
| reference_no | varchar(255) | yes |  |  |
| dispatch_id | bigint unsigned | no |  | FK |
| type | varchar(255) | yes |  |  |
| incident_severity | varchar(255) | yes |  |  |
| vehicle_id | bigint unsigned | no |  | FK |
| reported_by | varchar(255) | yes |  |  |
| reported_at | varchar(255) | yes |  |  |
| location | varchar(255) | yes |  |  |
| priority | varchar(255) | yes |  |  |
| status | varchar(255) | yes |  |  |
| description | longtext | yes |  |  |
| attachments | longtext | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: incidents_company_id_foreign (company_id); incidents_dispatch_id_foreign (dispatch_id); incidents_vehicle_id_foreign (vehicle_id)

Foreign keys: company_id -> companies.id (on delete: no action, on update: no action); dispatch_id -> dispatches.id (on delete: no action, on update: no action); vehicle_id -> vehicles.id (on delete: no action, on update: no action)

## makers

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| country | varchar(255) | no |  |  |
| name | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

## masteral_degrees

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| masteral_name | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: masteral_degrees_masteral_name_unique (masteral_name) UNIQUE

## model_has_permissions

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| permission_id | bigint unsigned | no |  | PK, FK |
| model_type | varchar(255) | no |  | PK |
| model_id | bigint unsigned | no |  | PK |

Indexes: model_has_permissions_model_id_model_type_index (model_id, model_type)

Foreign keys: permission_id -> permissions.id (on delete: cascade, on update: no action)

## model_has_roles

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| role_id | bigint unsigned | no |  | PK, FK |
| model_type | varchar(255) | no |  | PK |
| model_id | bigint unsigned | no |  | PK |

Indexes: model_has_roles_model_id_model_type_index (model_id, model_type)

Foreign keys: role_id -> roles.id (on delete: cascade, on update: no action)

## municipalities

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| province_id | bigint unsigned | no |  | FK |
| municipality_name | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: municipalities_province_id_foreign (province_id)

Foreign keys: province_id -> provinces.id (on delete: cascade, on update: cascade)

## nationalities

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| nationality | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: nationalities_nationality_unique (nationality) UNIQUE

## notifications

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | char(36) | no |  | PK |
| type | varchar(255) | no |  |  |
| notifiable_type | varchar(255) | no |  |  |
| notifiable_id | bigint unsigned | no |  |  |
| data | text | no |  |  |
| read_at | timestamp | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: notifications_notifiable_type_notifiable_id_index (notifiable_type, notifiable_id)

## odometers

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| dispatch_id | bigint unsigned | no |  | FK |
| vehicle_id | bigint unsigned | no |  | FK |
| odometer_in | varchar(255) | no |  |  |
| odometer_out | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: odometers_dispatch_id_foreign (dispatch_id); odometers_vehicle_id_foreign (vehicle_id)

Foreign keys: dispatch_id -> dispatches.id (on delete: cascade, on update: no action); vehicle_id -> vehicles.id (on delete: cascade, on update: no action)

## passengers

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| dispatch_id | bigint unsigned | no |  | FK |
| name | varchar(255) | yes |  |  |
| contact_no | varchar(255) | yes |  |  |
| pick_up_location | json | yes |  |  |
| pick_up_lat | decimal(10,7) | yes |  |  |
| pick_up_lng | decimal(10,7) | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: passengers_dispatch_id_foreign (dispatch_id)

Foreign keys: dispatch_id -> dispatches.id (on delete: cascade, on update: no action)

## permissions

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| name | varchar(255) | no |  |  |
| guard_name | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: permissions_name_guard_name_unique (name, guard_name) UNIQUE

## positions

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| position_no | varchar(255) | no |  |  |
| position_description | text | yes |  |  |
| department_id | bigint unsigned | no |  | FK |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: positions_department_id_foreign (department_id); positions_position_no_unique (position_no) UNIQUE

Foreign keys: department_id -> departments.id (on delete: cascade, on update: cascade)

## preventive_work_orders

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| vehicle_id | bigint unsigned | no |  | FK |
| job_order_no | varchar(255) | yes |  |  |
| job_order_date | date | yes |  |  |
| preventive_maintenance_type | varchar(255) | yes |  |  |
| job_order_assigned_date | date | yes |  |  |
| job_order_accomplished_date | date | yes |  |  |
| supervisor_id | bigint unsigned | no |  | FK |
| leadman_id | bigint unsigned | no |  | FK |
| engine_item | json | yes |  |  |
| steering_item | json | yes |  |  |
| brake_item | json | yes |  |  |
| exhaust_item | json | yes |  |  |
| front_suspension_item | json | yes |  |  |
| rear_axle_item | json | yes |  |  |
| clutch_item | json | yes |  |  |
| transmission_item | json | yes |  |  |
| propeller_item | json | yes |  |  |
| tire_item | json | yes |  |  |
| electrical_item | json | yes |  |  |
| body_item | json | yes |  |  |
| pms_tag_format | tinyint(1) | yes |  |  |
| pms_next_schedule | tinyint(1) | yes |  |  |
| odometer_reading | tinyint(1) | yes |  |  |
| plate_number_id | tinyint(1) | yes |  |  |
| driver_id | tinyint(1) | yes |  |  |
| date_of_pms | tinyint(1) | yes |  |  |
| pms_tagging | json | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |
| type | varchar(255) | yes |  |  |
| assignment | varchar(255) | yes |  |  |
| UCR_ref_no | varchar(255) | yes |  |  |
| UCR_amount | varchar(255) | yes |  |  |
| invoice | varchar(255) | yes |  |  |
| file_attachment | longtext | yes |  |  |

Indexes: preventive_work_orders_leadman_id_foreign (leadman_id); preventive_work_orders_supervisor_id_foreign (supervisor_id); preventive_work_orders_vehicle_id_foreign (vehicle_id)

Foreign keys: leadman_id -> employees.id (on delete: cascade, on update: no action); supervisor_id -> employees.id (on delete: cascade, on update: no action); vehicle_id -> vehicles.id (on delete: no action, on update: no action)

## provinces

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| region_id | bigint unsigned | no |  | FK |
| province_name | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: provinces_province_name_unique (province_name) UNIQUE; provinces_region_id_foreign (region_id)

Foreign keys: region_id -> regions.id (on delete: cascade, on update: cascade)

## regions

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| region_name | varchar(255) | no |  |  |
| region_description | varchar(255) | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: regions_region_name_unique (region_name) UNIQUE

## requesting_offices

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| office_name | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

## role_has_permissions

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| permission_id | bigint unsigned | no |  | PK, FK |
| role_id | bigint unsigned | no |  | PK, FK |

Indexes: role_has_permissions_role_id_foreign (role_id)

Foreign keys: permission_id -> permissions.id (on delete: cascade, on update: no action); role_id -> roles.id (on delete: cascade, on update: no action)

## roles

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| name | varchar(255) | no |  |  |
| guard_name | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: roles_name_guard_name_unique (name, guard_name) UNIQUE

## schools

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| school_name | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

## toll_fares

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| toll_road_id | bigint unsigned | no |  | FK |
| entry_point_id | bigint unsigned | no |  | FK |
| exit_point_id | bigint unsigned | no |  | FK |
| class | varchar(255) | no |  |  |
| fare | decimal(10,2) | no |  |  |
| discount | decimal(3,2) | no | 0.00 |  |
| is_active | tinyint(1) | no | 1 |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: toll_fares_entry_point_id_foreign (entry_point_id); toll_fares_exit_point_id_foreign (exit_point_id); unique_toll_fare (toll_road_id, entry_point_id, exit_point_id) UNIQUE

Foreign keys: entry_point_id -> toll_points.id (on delete: no action, on update: no action); exit_point_id -> toll_points.id (on delete: no action, on update: no action); toll_road_id -> toll_roads.id (on delete: no action, on update: no action)

## toll_points

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| toll_road_id | bigint unsigned | yes |  | FK |
| name | varchar(255) | no |  |  |
| type | enum('entry','exit','both') | no | entry |  |
| latitude | double | no |  |  |
| longitude | double | no |  |  |
| payment_method | json | yes |  |  |
| is_active | tinyint(1) | no | 1 |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: toll_points_toll_road_id_foreign (toll_road_id)

Foreign keys: toll_road_id -> toll_roads.id (on delete: set null, on update: no action)

## toll_roads

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| name | varchar(255) | no |  |  |
| operator | varchar(255) | yes |  |  |
| region | varchar(255) | yes |  |  |
| is_active | tinyint(1) | no | 1 |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

## tolls

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| dispatch_id | bigint unsigned | yes |  |  |
| toll_road_id | bigint unsigned | yes |  | FK |
| vehicle_class | varchar(255) | yes |  |  |
| entry_point_id | bigint unsigned | yes |  | FK |
| exit_point_id | bigint unsigned | yes |  | FK |
| payment_method | varchar(255) | yes |  |  |
| toll_fare | decimal(10,2) | yes |  |  |
| toll_attachments | longtext | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: tolls_entry_point_id_foreign (entry_point_id); tolls_exit_point_id_foreign (exit_point_id); tolls_toll_road_id_foreign (toll_road_id)

Foreign keys: entry_point_id -> toll_points.id (on delete: set null, on update: no action); exit_point_id -> toll_points.id (on delete: set null, on update: no action); toll_road_id -> toll_roads.id (on delete: set null, on update: no action)

## users

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| employee_no | varchar(50) | yes |  |  |
| name | varchar(255) | no |  |  |
| email | varchar(255) | no |  |  |
| email_verified_at | timestamp | yes |  |  |
| password | varchar(255) | no |  |  |
| app_authentication_secret | text | yes |  |  |
| has_email_authentication | tinyint(1) | no | 0 |  |
| app_authentication_recovery_codes | text | yes |  |  |
| role_id | varchar(255) | yes |  |  |
| is_active | tinyint(1) | no | 1 |  |
| remember_token | varchar(100) | yes |  |  |
| deleted_at | timestamp | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |
| custom_fields | json | yes |  |  |
| avatar_url | varchar(255) | yes |  |  |
| locale | varchar(255) | yes |  |  |
| theme_color | varchar(255) | yes |  |  |

Indexes: users_email_unique (email) UNIQUE

## vehicle_categories

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| name | varchar(255) | no |  |  |
| description | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

## vehicle_energy_logs

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| dispatch_id | bigint unsigned | no |  | FK |
| vehicle_id | bigint unsigned | no |  | FK |
| reference_no | varchar(255) | yes |  |  |
| power_type_id | bigint unsigned | no |  | FK |
| date | date | no |  |  |
| cost | decimal(10,2) | no |  |  |
| attachment | json | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: vehicle_energy_logs_dispatch_id_foreign (dispatch_id); vehicle_energy_logs_power_type_id_foreign (power_type_id); vehicle_energy_logs_vehicle_id_foreign (vehicle_id)

Foreign keys: dispatch_id -> dispatches.id (on delete: no action, on update: no action); power_type_id -> vehicle_power_types.id (on delete: no action, on update: no action); vehicle_id -> vehicles.id (on delete: no action, on update: no action)

## vehicle_groups

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| name | varchar(255) | no |  |  |
| description | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

## vehicle_power_types

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| name | varchar(255) | no |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

## vehicles

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| charge_account_id | bigint unsigned | yes |  | FK |
| company_id | bigint unsigned | yes |  | FK |
| business_unit_id | bigint unsigned | yes |  | FK |
| ownership | varchar(255) | yes |  |  |
| plate_no | varchar(255) | yes |  |  |
| device_sn | varchar(255) | yes |  |  |
| init_odo | varchar(255) | yes |  |  |
| maker_id | bigint unsigned | yes |  | FK |
| model | varchar(255) | yes |  |  |
| year | varchar(255) | yes |  |  |
| status | varchar(255) | yes |  |  |
| vehicle_category_id | bigint unsigned | yes |  | FK |
| vehicle_power_type_id | bigint unsigned | yes |  | FK |
| vehicle_group_id | bigint unsigned | yes |  | FK |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |

Indexes: vehicles_business_unit_id_foreign (business_unit_id); vehicles_charge_account_id_foreign (charge_account_id); vehicles_company_id_foreign (company_id); vehicles_maker_id_foreign (maker_id); vehicles_vehicle_category_id_foreign (vehicle_category_id); vehicles_vehicle_group_id_foreign (vehicle_group_id); vehicles_vehicle_power_type_id_foreign (vehicle_power_type_id)

Foreign keys: business_unit_id -> business_units.id (on delete: cascade, on update: no action); charge_account_id -> charge_accounts.id (on delete: cascade, on update: no action); company_id -> companies.id (on delete: cascade, on update: no action); maker_id -> makers.id (on delete: cascade, on update: no action); vehicle_category_id -> vehicle_categories.id (on delete: cascade, on update: no action); vehicle_group_id -> vehicle_groups.id (on delete: cascade, on update: no action); vehicle_power_type_id -> vehicle_power_types.id (on delete: cascade, on update: no action)

## work_orders

| Column | Type | Null | Default | Key |
| --- | --- | --- | --- | --- |
| id | bigint unsigned | no |  | PK |
| job_order_id | varchar(255) | yes |  |  |
| company_id | varchar(255) | yes |  |  |
| contracted_attachment | longtext | yes |  |  |
| start_date | date | yes |  |  |
| end_date | varchar(255) | yes |  |  |
| contract_amount | double | yes |  |  |
| contact_person_name | varchar(255) | yes |  |  |
| contact_person_email | varchar(255) | yes |  |  |
| contact_person_no | varchar(255) | yes |  |  |
| created_at | timestamp | yes |  |  |
| updated_at | timestamp | yes |  |  |
