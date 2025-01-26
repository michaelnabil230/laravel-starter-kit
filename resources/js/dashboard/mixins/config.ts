import { AppConfig } from '../types/appConfig';

export default function config<T>(key: keyof AppConfig): T {
    return dashboardApp.config<T>(key);
}
