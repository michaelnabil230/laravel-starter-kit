<?php

declare(strict_types=1);

return [
    'dashboard' => 'Dashboard',
    'home' => 'Home',
    'logout' => 'Logout',
    'profile' => [
        'name' => 'Profile',
        'description' => 'Edit your account details and password.',
        'personal_info' => [
            'title' => 'Personal Info',
            'description' => 'Edit your personal information and email.',
            'placeholder' => [
                'name' => 'Enter your name',
                'email' => 'Enter your email',
            ],
            'toast' => [
                'updated' => 'Your profile has been updated successfully!',
            ],
        ],
        'password' => [
            'title' => 'Password',
            'placeholder' => [
                'current_password' => 'Enter your current password',
                'new_password' => 'Enter your new password',
                'new_password_confirmation' => 'Confirm your new password',
            ],
            'button' => 'Change Password',
            'toast' => [
                'updated' => 'Your password has been updated.',
            ],
        ],
        'delete_account' => [
            'title' => 'Delete Account',
            'description' => 'Danger zone',
            'button' => [
                'text' => 'Delete my account',
                'description' => 'This will immediately delete all of your data. This action is irreversible, so proceed with caution.',
            ],
            'model' => [
                'title' => 'Delete Account',
                'description' => 'Are you sure you want to delete your account? Once deleted, all your data will be permanently erased. Please enter your password to confirm this action.',
            ],
            'toast' => [
                'deleted' => 'Your account has been deleted.',
            ],
        ],
    ],
    'authentication' => [
        'login' => [
            'name' => 'Login',
            'title' => 'Log in to your :appName account',
            'description' => 'Access digital products with :appName.',
            'submit' => 'Login',
            'forgot_your_password' => 'Forgot your password?',
        ],
        'forgot_password' => 'Forgot Password',
        'confirm_password' => 'Confirm Password',
    ],
    'resources' => [
        'admin' => [
            'plural' => 'Admins',
            'singular' => 'Admin',
            'attributes' => [
                'name' => 'Name',
                'phone' => 'Phone',
                'role' => 'Role',
            ],
            'enums' => [
                'role' => [
                    'admin' => 'ADMIN',
                    'super_admin' => 'Super Admin',
                ],
            ],
        ],
        'user' => [
            'plural' => 'Users',
            'singular' => 'User',
            'attributes' => [
                'name' => 'Name',
                'phone' => 'Phone',
                'birth_date' => 'Birth Date',
            ],
        ],
    ],
    'global' => [
        'id' => 'ID',
        'count' => [
            'name' => 'Count',
            'resource' => 'Count :resource',
        ],
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'deleted_at' => 'Deleted At',
        'attributes' => [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'current_password' => 'Current Password',
            'password' => 'Password',
            'password_confirmation' => 'Confirm Password',
            'new_password' => 'New Password',
            'new_password_confirmation' => 'Confirm New Password',
            'payment_type' => 'Payment type',
            'payment_method' => 'Payment method',
            'employment_type' => 'Employment type',
            'media' => 'Media',
        ],
        'placeholder' => 'Enter the :attribute',
        'example' => 'Example: :example',
        'loading' => 'Loading',
        'select_all' => 'Select All',
        'choose' => 'Choose :attribute',
        'download' => 'Download',
        'search' => [
            'name' => 'Search',
            'resource' => 'Search :resource',
            'global' => [
                'label' => 'Global Search',
                'resources' => [
                    'admins' => 'Admins',
                    'users' => 'Users',
                ],
            ],
        ],
        'not_found' => [
            'results' => 'No results found',
            'resource' => 'No :resource found',
            'description' => 'Please try a different search term or create a new one.',
        ],
        'crud' => [
            'edit' => 'Edit',
            'update' => 'Update',
            'delete' => 'Delete',
            'show' => 'Show',
            'create' => 'Create',
            'save' => 'Save',
        ],
        'closure' => [
            'cancel' => 'Cancel',
            'close' => 'Close',
            'to_close' => 'To Close',
        ],
        'confirmation' => [
            'yes' => 'Yes',
            'no' => 'No',
        ],
        'resource' => [
            'show' => 'Show :resource',
            'create' => 'Create :resource',
            'edit' => 'Edit :resource',
            'delete' => 'Delete :resource',
        ],
        'actions' => [
            'created' => 'The :resource was created!',
            'updated' => 'The :resource was updated!',
            'deleted' => 'The :resource was deleted!',
            'restored' => 'The :resource was restored!',
        ],
        'options' => [
            'delete' => [
                'delete_resource' => 'Delete Resource',
                'soft_deleted' => 'Soft Deleted',
                'force_delete' => 'Force Delete',
                'force_delete_resource' => 'Force Delete Resource',
                'force_delete_selected' => 'Force Delete Selected',
                'delete_selected' => 'Delete Selected',
            ],
            'restore' => [
                'restore_resource' => 'Restore Resource',
                'restore' => 'Restore',
                'restore_selected' => 'Restore Selected',
            ],
            'detach' => [
                'detach_resource' => 'Detach Resource',
                'detach' => 'Detach',
                'detach_selected' => 'Detach Selected',
            ],
        ],
        'gender' => 'Gender',
        'day' => 'Day',
        'weekend' => 'Weekend',
        'attendance_methods' => 'Attendance Methods',
        'enums' => [
            'gender' => [
                'male' => 'Male',
                'female' => 'Female',
            ],
            'day' => [
                'saturday' => 'Saturday',
                'sunday' => 'Sunday',
                'monday' => 'Monday',
                'tuesday' => 'Tuesday',
                'wednesday' => 'Wednesday',
                'thursday' => 'Thursday',
                'friday' => 'Friday',
            ],
        ],
        'file_upload' => [
            'single_file_only' => 'Only one file can be uploaded at a time',
            'max_files_exceeded' => 'Maximum :count file(s) allowed',
            'file_too_large' => 'File :name exceeds maximum size of :size MB',
            'invalid_file_type' => 'File type :type is not allowed',
            'drop_here_or' => 'Drop your file here or',
            'browse' => 'Browse',
            'max_size' => 'Pick a file up to :size MB',
            'allowed_types' => 'Allowed types: :types',
            'upload_failed' => 'Failed to upload file',
        ],
    ],
    'import_and_export' => 'Import / Export',
    'export' => [
        'label' => 'Export',
        'resource' => 'Export :resource',
        'model' => [
            'title' => 'Choose to Export Either the Current Page or All Available Records in .XLSX Format',
            'current_page' => 'Current Page',
            'all_resource' => 'All :resource',
            'confirm' => 'Confirm Export',
        ],
        'success' => 'An email will be sent to your account with the file once the export is ready.',
    ],
    'import' => [
        'label' => 'Import',
        'resource' => 'Import :resource',
        'model' => [
            'example' => [
                'first' => 'Download a sample CSV template',
                'second' => 'to see the required format.',
            ],
            'confirm' => 'Confirm Import',
        ],
    ],
    'models' => [
        'delete' => [
            'title' => 'Are you sure you want to delete this :resource?',
            'description' => 'You can undo this action later by deleting this resource.',
        ],
        'delete_all' => [
            'title' => 'Are you sure you want to delete all selected :resource?',
            'description' => 'You can undo this action later by deleting the selected resources.',
        ],
    ],
    'pagination' => [
        'showing' => 'Showing',
        'page' => 'Page',
        'next' => 'Next',
        'previous' => 'Previous',
        'of' => 'of',
    ],
    'filters' => [
        'name' => 'Filter',
        'apply' => 'Apply',
        'reset' => 'Reset',
        'clear' => 'Clear',
        'trashed' => [
            'name' => 'Trashed',
            'with' => 'With Trashed',
            'only' => 'Only Trashed',
        ],
    ],
    'navigation' => [
        'go_home' => 'Go Home',
        'go_to_link' => 'Go to link',
    ],
    'photo' => 'Photo',
    'upload_photo' => 'Upload Photo',
    'upload' => 'Upload',
    'browse' => 'Browse',
    'errors' => [
        'error' => 'Error',
        'error_happened' => 'An error occurred, please try again later.',
    ],
    'all_rights_reserved' => 'All Rights Reserved',
    'faq' => 'FAQ',
    'license' => 'License',
    'back_to_home' => 'Back to Home',
];
