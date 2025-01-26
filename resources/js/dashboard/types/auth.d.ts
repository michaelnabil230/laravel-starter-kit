import { Role } from './enums/admin';

export interface Auth {
    id: string;
    name: string;
    email: string;
    email_verified_at: string;
    photo: string | null;
    profile_photo_url: string;
    role: Role;
    created_at: string;
    updated_at: string;
}
