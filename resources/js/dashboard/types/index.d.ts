import { Auth } from './auth';
import { Modal } from './global';
import { Toast } from './toast';

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        admin: Auth;
        can: {
            owner: boolean;
        };
    };
    toast: Toast;
    modal: Modal;
};
