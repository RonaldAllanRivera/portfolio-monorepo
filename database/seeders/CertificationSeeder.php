<?php

namespace Database\Seeders;

use App\Models\Certification;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->first();
        if (! $user) {
            $this->command?->warn('No users found. Run FilamentAdminSeeder first.');
            return;
        }

        $org = Organization::firstOrCreate(
            ['name' => 'LinkedIn Learning'],
            ['website' => 'https://www.linkedin.com/learning/', 'sort_order' => 0]
        );

        $rows = [
            ['name' => 'Building Modern Projects with React', 'issue_date' => '2025-08-27', 'credential_id' => '33d7b534ce141004f955319fedc3d5ec508815079f2acfaada174e26720746ce'],
            ['name' => 'Building a Website with Laravel, React.js, and Inertia', 'issue_date' => '2025-08-14', 'credential_id' => 'b01a53cc90b8135336242263048fafd652382098f874598b1e0de2ffbfc00a76'],
            ['name' => 'HTMX Essential Training', 'issue_date' => '2025-08-04', 'credential_id' => 'c7de5adcb5d2827eb37d72599c4f6cb43956bfbd9d2de3bc8026589d005b2604'],
            ['name' => 'Custom WordPress Plugins: Design, Develop, and Distribute', 'issue_date' => '2025-08-07', 'credential_id' => 'f5fe062fe2ae57d3cd98db101d2f19e23eb0a17f3e32d35ad6535518d2e189cd'],
            ['name' => 'GitHub Actions for CI/CD', 'issue_date' => '2025-07-30', 'credential_id' => '45c1cc155f56f1c100e0356aa95ff620d4cfdbc857b684f2c2db1144daa824a7'],
            ['name' => 'Fundamentals of AI Engineering: Principles and Practical Applications', 'issue_date' => '2025-07-24', 'credential_id' => 'b924f96937612ae3b362e862fb27380503b4057a8f6969e33722f3dd0e813db6'],
            ['name' => 'Model Context Protocol (MCP): Hands-On with Agentic AI', 'issue_date' => '2025-07-21', 'credential_id' => 'ecc4f8cdb8d8a0af3e3587f8bfe940c97c73a50deca72279304188e028f9005e'],
            ['name' => 'Learning Kubernetes', 'issue_date' => '2025-07-20', 'credential_id' => '9e9010446496f38f2a05668e156edea5e8afc63654e40379b8d9566408c45043'],
            ['name' => 'DevOps Foundations: Continuous Delivery /Continuous Integration', 'issue_date' => '2025-07-16', 'credential_id' => 'f7bb22c2c0dbc59c15e8459628832a3ea28be99c46549e210a9180ba8b0a4606'],
            ['name' => 'AWS API Gateway with HTTP, Lambda, DynamoDB, and iOS', 'issue_date' => '2025-07-11', 'credential_id' => 'e70d37f0cafe8215b0a603f724df137427dfe024b46236a51e596655b8dfda47'],
            ['name' => 'AWS Certified Cloud Practitioner (CLF-C02) Cert Prep: 1 Cloud Concepts', 'issue_date' => '2025-07-20', 'credential_id' => 'f8516ab967efb579f4ba5205e1dcdfec26c1eeb1d2fe836e7522b506f57a92d9'],
            ['name' => 'MongoDB Essential Training', 'issue_date' => '2025-07-08', 'credential_id' => '05067d9ffbe1acc92103ebb46d7a2940efd531ae6d3b5e559ce8aa724da2a2e6'],
            ['name' => 'TypeScript Essential Training', 'issue_date' => '2025-06-25', 'credential_id' => 'ae62321a86cb8a391af54aa71def55ca67390407ed91e08c06924a2c79bebc78'],
            ['name' => 'Express Essentials: Build Powerful Web Apps with Node.js', 'issue_date' => '2025-06-27', 'credential_id' => 'b1758b031c4ab641b173d96d7d4536251e815713c93535796e08950b6538ee69'],
            ['name' => 'Next.js: Creating and Hosting a Full-Stack Site', 'issue_date' => '2025-06-21', 'credential_id' => 'a86740c29838ab340fae7f881a666d88bf99ca598c36bcce671d4ca247d5ea81'],
            ['name' => 'Building GraphQL Applications in Laravel', 'issue_date' => '2024-12-05', 'credential_id' => '82c64cb0e42918b944984e9daa13be9f88325cdc47535549d5d8840986343fdd'],
            ['name' => 'Django Essential Training', 'issue_date' => '2024-11-19', 'credential_id' => '914245da3488fe74cc988207eeedb297e566cd0d8727618a3e5083e905ac4fa4'],
            ['name' => 'Learning npm: A Package Manager', 'issue_date' => '2024-12-20', 'credential_id' => '32db740d99020a763effb933faa2c8bb4d35b96f8fc9fde3284a91596e506b76'],
            ['name' => 'Practical GitHub Copilot', 'issue_date' => '2024-12-30', 'credential_id' => 'c7bf4d6b59f23ca0741527c6241e83b1e64de1c9820ba20000bc0bffc1a7373c'],
            ['name' => 'Figma Essential Training', 'issue_date' => '2025-05-15', 'credential_id' => '15e5309e9120aab099bd164f5a2f1f8e290866f74755535da1dc6487e19810d4'],
            ['name' => 'Premiere Pro 2025 Essential Training', 'issue_date' => '2025-03-14', 'credential_id' => '5e38de98dd380e18463af59262e59c08ae876b9a22d426e7da0b9b57f4919fc2'],
            ['name' => 'Video Editing Fundamentals', 'issue_date' => '2025-03-23', 'credential_id' => 'f355db89fb691abc0138293d43b50fb689c14775fbff8e0dfaa542e91dc28c8a'],
            ['name' => 'WordPress: Custom Post Types and Taxonomies', 'issue_date' => '2024-12-18', 'credential_id' => '2f6601a67d2327b1fc3e3ea54c81dc29299cdc54b447327db40a70caac345d70'],
            ['name' => 'Agile Foundations', 'issue_date' => '2024-12-15', 'credential_id' => 'c5189bc0ba85a7e6016059ad1f93e1683f304106df08fa7b006bf1b1647b5f04'],
            ['name' => 'Building RESTful APIs in Laravel', 'issue_date' => '2024-12-04', 'credential_id' => '5e6d5ce87925d79cb32aae390f17d62e04850bc609e62be99d04885da01e25f9'],
            ['name' => 'Level Up: Python', 'issue_date' => '2024-11-19', 'credential_id' => 'f10790e4b3c999dbb050501bdfb94bf9737bf3c5bba553a2a30cfe6b167159d1'],
            ['name' => 'Deep Learning Foundations: Natural Language Processing with TensorFlow', 'issue_date' => '2025-03-10', 'credential_id' => '28580b41cff6f96e90dc5aba8174f54ed3a9f6dcccf5db71b9bac4347df66d72'],
            ['name' => 'Fundamentals to Become a Machine Learning Engineer', 'issue_date' => '2025-02-27', 'credential_id' => '6f14c524435962cc935f269cc048c73afdc84b05238a7616884ba9c86f01eb1c'],
            ['name' => 'Securing Software as a Service (SaaS)', 'issue_date' => '2025-05-25', 'credential_id' => '5c34cfc123c6e93e529492dfd890e2e04ed4042edf9cf4c548954dac95855bfc'],
            ['name' => 'Git Essential Training', 'issue_date' => '2024-12-27', 'credential_id' => '1e890028719654b52cc922ca5b4464005a4ce2db8d3be0f3561ba01a0fde04b5'],
            ['name' => 'Laravel: Building a CRM with Filament for Laravel', 'issue_date' => '2024-12-14', 'credential_id' => '1f3d00496216aa4d7d8b3c64c5e75cf387ac213564c66114fafd5ae59a661f76'],
            ['name' => 'Building Angular and Django Apps', 'issue_date' => '2025-05-10', 'credential_id' => '161d0c6326d33016eca724de3899156fe1e22964a825f84ff6ffd94d2c857a7e'],
            ['name' => 'Practical GitHub Code Search', 'issue_date' => '2025-01-01', 'credential_id' => '559dc4348e18461307db7d714b9bdbd856cc47c2ae25d5864e79979d23783a06'],
            ['name' => 'React Essential Training', 'issue_date' => '2024-12-08', 'credential_id' => 'e89adcf61aef250dc56a97161dd4b05c2038edced33ea10493b0b816a8b6958c'],
            ['name' => 'Become an AI Engineer', 'issue_date' => '2025-03-12', 'credential_id' => '4268173311ad8a9beade4470e5f3deb30aa070323210fda19a37805c4a43c54d'],
            ['name' => 'Django Essential Training (2025)', 'issue_date' => '2025-05-23', 'credential_id' => 'af807f85a18de4d27c1e4e9df3c47b064689149f11df7e2c8975a7eb5998f96a'],
            ['name' => 'Python Essential Training', 'issue_date' => '2024-11-17', 'credential_id' => '71ec9ce81c088ab9086beb5510dee27de4eee37e15a4ca1cda0cc4642b0d5633'],
            ['name' => 'Tailwind CSS 4 Essential Training', 'issue_date' => '2025-06-02', 'credential_id' => '111a5b9612a3e040339084651ebbf02811648b132ff0451952202e6545295ca0'],
            ['name' => 'WordPress: Building a Secure Site', 'issue_date' => '2024-12-09', 'credential_id' => '81f10cb5e17866fd99d6f2c15f2c7e7838520e89bff044a6b85ec99cc7ee5e70'],
            ['name' => 'WordPress Essential Training', 'issue_date' => '2025-05-14', 'credential_id' => '8887227bf6002282146185cad81ee8e1da875895293da1822b5ac1f8669597cf'],
            ['name' => 'Laravel Essential Training', 'issue_date' => '2024-11-27', 'credential_id' => 'a62afe7be32b1289dcf55271659faa0b1538345ab4a60ca27b31afd070f72653'],
            ['name' => 'Vibe Coding Fundamentals: Tools and Best Practices', 'issue_date' => '2025-06-05', 'credential_id' => 'a9342688d934995e9c94a3b50ebc4e9f17aba8c8112495cb2d23ea881ba76122'],
            ['name' => 'Building React and Django Apps', 'issue_date' => '2025-05-08', 'credential_id' => '281091f4ba76c7ce27429f5187229bec80a4b58e2d14001814334fd7f6f87cc0'],
            ['name' => 'Node.js: Testing and Code Quality', 'issue_date' => '2024-12-25', 'credential_id' => 'ed0dd5a3a4d81b09e6387e4f321e7d9602ad55868a59edd50c362cdd8589cbde'],
            ['name' => 'AI and Generative AI for Video Content Creation', 'issue_date' => '2025-03-22', 'credential_id' => '238455ae1d22c558a63a8391a7f77702158c1c00ad4544167a88540d45ccac3a'],
            ['name' => 'Docker for Developers', 'issue_date' => '2025-05-30', 'credential_id' => '37d41dd7fed5ab1665d2b9441537ecc448996fe5b34fb53c2a5491654068edcb'],
            ['name' => 'Advanced Laravel', 'issue_date' => '2024-12-02', 'credential_id' => 'b70e683da49224fac61c5dd4e9c5f442a3b9519a66223f8927958e42c12eef9e'],
            ['name' => 'Explore React.js Development', 'issue_date' => '2025-02-11', 'credential_id' => 'c3e08e5bfbfa580df0208ec900acfdb6594ea4f00efe23d0162213b461332c66'],
            ['name' => 'Practical GitHub Project Management and Collaboration', 'issue_date' => '2024-12-29', 'credential_id' => '353a09617244451b65cbf40aa1e8ff6b81d645cdd156bfb319e2be3b3bcece8f'],
            ['name' => 'Using Python for Automation', 'issue_date' => '2024-11-20', 'credential_id' => '56db14e533a3fdcc56dc872cc6501e193d4827f4bfa399a3b55cb71d5fb5d563'],
            ['name' => 'Livewire Essential Training', 'issue_date' => '2024-12-11', 'credential_id' => '2f70f1c665385a9b981264318c8bf3f5ef2a26cc562932cb052bc1d64aa9c85d'],
            ['name' => 'Practical GitHub Actions', 'issue_date' => '2024-12-29', 'credential_id' => 'e40208dfb0d72497af6803f2162dc0a9db0ae89866a4b42ad18a7664d21dba83'],
            ['name' => 'Introduction to Video Editing', 'issue_date' => '2025-03-28', 'credential_id' => '9bb8157b817c27c832ad0d7d645ec3b6759afa19b8aa6b428cd5a70b1ce5c344'],
            ['name' => 'Node.js Essential Training', 'issue_date' => '2024-12-19', 'credential_id' => '0c86dd65b36e45e63017947a29f1d3c187a7411779ec1fe5c3c815852422a2dc'],
            ['name' => 'Python Hands-On Practice', 'issue_date' => '2025-04-29', 'credential_id' => '7eee12decca890f1993a6cc111c0b6a92d38d970629e240a0a0e35740cdff3df'],
            ['name' => 'Software Architecture Foundations', 'issue_date' => '2025-05-26', 'credential_id' => '19766d900f59c253530bf7b16568826b9538711edd0684214d0bf0ab99f87dd8'],
            ['name' => 'Advanced Python: Working with Databases', 'issue_date' => '2025-05-04', 'credential_id' => '38bc91603bce3d69fc80a77415e1ea2e65870bd43364dcb64090708530d79b43'],
            ['name' => 'Building PHP Applications with Generative AI', 'issue_date' => '2024-12-28', 'credential_id' => '0b2ee6d4dbc24eae8a69c3227cceddaa2b4a59303e4e9ae2732e4c834c78ada7'],
            ['name' => 'AWS Essential Training for Developers', 'issue_date' => '2025-06-06', 'credential_id' => '21a88cee8041f1495a604a171df1283a7f6ffc7a3443a22f5047c57f61fd6ac1'],
            ['name' => 'Leveraging AI in Adobe Premiere Pro', 'issue_date' => '2025-03-21', 'credential_id' => '61bc6ccdf7a9524aeac01763f920ce2f04d51325fd32625bf87d94fa3428365f'],
            ['name' => 'React.js: Building an Interface', 'issue_date' => '2025-01-04', 'credential_id' => '2c3409c0ce3da8e46590b436f490ee70b7a0f4ea17fa19741886f0a2d0375ddd'],
            ['name' => 'WordPress: Internationalization', 'issue_date' => '2024-12-10', 'credential_id' => 'c4f2b4f22693e61f9388f4a4873f42eb13f325d37ee6cdff6e7c19fdd88905b9'],
            ['name' => 'Advanced Python: Practical Database Examples', 'issue_date' => '2025-05-07', 'credential_id' => 'c444a48013cdb95fa9c3416f1c21aa9777a5472e2620f0a0b47a662429a37afe'],
        ];

        $sort = 0;
        foreach ($rows as $row) {
            Certification::updateOrCreate(
                ['credential_id' => $row['credential_id']],
                [
                    'user_id' => $user->id,
                    'organization_id' => $org->id,
                    'name' => $row['name'],
                    'issue_date' => $row['issue_date'],
                    'credential_id' => $row['credential_id'],
                    'sort_order' => $sort++,
                ]
            );
        }
    }
}
