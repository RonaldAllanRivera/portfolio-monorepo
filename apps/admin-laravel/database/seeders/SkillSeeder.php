<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Frontend Development' => [
                'HTML5', 'CSS3', 'JavaScript (ES6+)', 'DOM Manipulation', 'Responsive Web Design',
                'Cross-Browser Compatibility', 'Accessibility (ARIA, WCAG)', 'React.js', 'Vue.js', 'Angular',
                'Next.js', 'Nuxt.js', 'Bootstrap', 'Tailwind CSS', 'Material UI',
            ],
            'Backend Development' => [
                'PHP', 'Laravel', 'CodeIgniter', 'Symfony', 'Node.js', 'Express.js', 'NestJS',
                'Python (Django, Flask)', 'Ruby on Rails', 'RESTful APIs', 'GraphQL',
                'Authentication (JWT, OAuth2)', 'Security (CSRF, XSS, SQL Injection Prevention)',
            ],
            'Databases' => [
                'MySQL', 'PostgreSQL', 'MariaDB', 'SQLite', 'MongoDB', 'Firebase', 'Redis',
                'Database Design & Normalization', 'Query Optimization',
            ],
            'DevOps & Deployment' => [
                'Apache', 'Nginx', 'Docker', 'Kubernetes', 'VPS / Dedicated Servers', 'cPanel / Plesk',
                'AWS (EC2, S3, RDS, Lambda)', 'Google Cloud Platform (GCP)', 'Microsoft Azure',
                'Railway / Render / Heroku', 'GitHub Actions', 'Jenkins', 'CI/CD Pipelines',
            ],
            'Tools & Workflow' => [
                'Git (GitHub, GitLab, Bitbucket)', 'Bash / Shell Scripting', 'Composer (PHP)',
                'npm / Yarn (JavaScript)', 'Webpack', 'Vite', 'Parcel', 'Postman / Insomnia',
                'Browser DevTools', 'Testing (PHPUnit, Jest, Mocha, Cypress)', 'Debugging & Logging',
            ],
            'CMS & E-commerce' => [
                'WordPress', 'Shopify', 'WooCommerce', 'Drupal', 'Magento', 'Headless CMS (Strapi, Contentful)',
            ],
            'Performance & Optimization' => [
                'Caching (Redis, Varnish)', 'CDN (Cloudflare, Akamai)', 'Lazy Loading', 'Image Optimization',
                'Minification & Bundling', 'SEO Best Practices',
            ],
            'UX/UI & Design' => [
                'Figma', 'Adobe Photoshop', 'Adobe Illustrator', 'Wireframing', 'Prototyping',
                'User Experience Principles',
            ],
            'Mobile & Modern Apps' => [
                'Progressive Web Apps (PWA)', 'React Native', 'Flutter (basic knowledge)', 'Service Workers',
                'WebSockets (real-time apps)',
            ],
            'Project Management & Soft Skills' => [
                'Agile / Scrum', 'Kanban', 'Jira / Trello / Asana', 'Time Management', 'Problem-Solving',
                'Collaboration & Communication', 'Technical Documentation',
            ],
        ];

        foreach ($data as $category => $skills) {
            foreach ($skills as $index => $name) {
                Skill::updateOrCreate(
                    ['name' => $name],
                    ['category' => $category, 'sort_order' => $index]
                );
            }
        }
    }
}
