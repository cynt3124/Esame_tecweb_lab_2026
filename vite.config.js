import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/css/page_loader.css',
                'resources/js/app.js',

                'resources/js/validation.js',
                'resources/js/delete_alert.js',
                'resources/js/page_loader.js',

                'resources/js/chart/department_employee.js',
                'resources/js/chart/project_department.js',
                'resources/js/chart/project_employee.js',

                'resources/js/ajax/delete_alert.js',
                'resources/js/ajax/create_departments.js',
                'resources/js/ajax/create_projects.js',
                'resources/js/ajax/create_works_on.js'
                
            ],
            refresh: true,
        }),
    ],
});
