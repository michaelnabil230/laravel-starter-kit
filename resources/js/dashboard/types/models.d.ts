import { Role } from './enums/admin';
import { Gender } from './enums/gender';

declare namespace App.Models {
    export interface Admin {
        id: string;
        name: string;
        email: string;
        phone: string;
        phone_country: string;
        photo: ?string;
        profile_photo_url: string;
        role: Role;
        created_at: string;
        updated_at: string;
    }

    export interface User {
        id: string;
        name: string;
        email: string;
        phone: string;
        phone_country: string;
        email_verified_at: string;
        photo: ?string;
        profile_photo_url: string;
        birth_date: string;
        gender: Gender;
        created_at: string;
        updated_at: string;
    }
}

declare namespace App {
    export interface PaginateLink {
        url: string;
        label: string;
        active: boolean;
    }

    export interface Paginate<T> {
        data: T[];
        current_page: number;
        first_page_url: string;
        from: number;
        last_page: number;
        last_page_url: string;
        links: PaginateLink[];
        next_page_url: string;
        path: string;
        per_page: number;
        prev_page_url: string;
        to: number;
        total: number;
    }

    export interface ApiPaginate<T> {
        data: T[];
        links: {
            first?: string;
            last?: string;
            prev?: string;
            next?: string;
        };
        meta: {
            current_page: number;
            from: number;
            last_page: number;
            links: PaginateLink[];
            path: string;
            per_page: number;
            to: number;
            total: number;
        };
    }
}
