export namespace App {
    export namespace Models {
        export interface Admin {
            id: string;
            name: string;
            email: string;
            phone: string;
            phone_country: string;
            photo?: string;
            profile_photo_url: string;
            role: App.Enums.Role;
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
            photo?: string;
            profile_photo_url: string;
            birth_date: string;
            gender: App.Enums.Gender;
            created_at: string;
            updated_at: string;
        }

        export interface Media {
            id: number;
            uuid: string;
            url: string;
            preview: string;
            name: string;
            file_name: string;
            type: App.Enums.MediaType;
            mime_type: string;
            size: number;
            human_readable_size: string;
            details: {
                width?: number;
                height?: number;
                ratio?: number;
                duration?: number;
            };
            conversions?: {
                [key: string]: string;
            };
        }
    }

    export namespace Enums {
        export enum Gender {
            MALE = 'male',
            FEMALE = 'female',
        }

        export enum Day {
            SUNDAY = 'sunday',
            MONDAY = 'monday',
            TUESDAY = 'tuesday',
            WEDNESDAY = 'wednesday',
            THURSDAY = 'thursday',
            FRIDAY = 'friday',
            SATURDAY = 'saturday',
        }

        export enum Role {
            ADMIN = 'admin',
            SUPER_ADMIN = 'super_admin',
        }

        export type MediaType = 'image' | 'video' | 'audio' | string;
    }

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
