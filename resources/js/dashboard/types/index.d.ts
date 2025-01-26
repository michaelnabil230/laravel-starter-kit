import { Auth } from './auth';
import { Toast } from './toast';

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        admin: Auth;
        can: {
            owner: boolean;
        };
    };
    toast: Toast;
};
