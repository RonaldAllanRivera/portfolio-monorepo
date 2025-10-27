<?php

namespace Database\Seeders;

use App\Models\Certification;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CertificationsEmbeddedSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->first();
        if (! $user) {
            return;
        }

        if (! app()->environment('production')) {
            DB::table('certification_skill')->truncate();
            Certification::query()->delete();
        }

        // Embed the reviewed CSV content directly (quoted and multiline fields preserved)
        $csv = <<<'CSV'
Page,Name,Issuer,Issue date,Credential ID,Hours,Minutes,Skills
1,Building Modern Projects with React,LinkedIn Learning,"Aug 27, 2025",33d7b534ce141004f955319fedc3d5ec508815079f2acfaada174e26720746ce,3,50,"Web, Development, JavaScript, Front-End, Development"
2,"Building a Website with Laravel,
React.js, and Inertia",LinkedIn Learning,"Aug 14, 2025",b01a53cc90b8135336242263048fafd652382098f874598b1e0de2ffbfc00a76,1,16,"Web, Development, React.js, Laravel"
3,HTMX Essential Training,LinkedIn Learning,"Aug 04, 2025",c7de5adcb5d2827eb37d72599c4f6cb43956bfbd9d2de3bc8026589d005b2604,1,24,"Web, Development, Front-End, Development, HTML"
4,"Custom WordPress Plugins: Design,
Develop, and Distribute",LinkedIn Learning,"Aug 07, 2025",f5fe062fe2ae57d3cd98db101d2f19e23eb0a17f3e32d35ad6535518d2e189cd,1,13,"WordPress, Design, WordPress"
5,GitHub Actions for CI/CD,LinkedIn Learning,"Jul 30, 2025",45c1cc155f56f1c100e0356aa95ff620d4cfdbc857b684f2c2db1144daa824a7,2,2,"GitHub, Continuous, Integration and, Continuous, Delivery (CI/CD)"
6,"Fundamentals of AI Engineering:
Principles and Practical Applications",LinkedIn Learning,"Jul 24, 2025",b924f96937612ae3b362e862fb27380503b4057a8f6969e33722f3dd0e813db6,4,3,"Artificial, Intelligence (AI), Generative, AI"
7,"Model Context Protocol (MCP):
Hands-On with Agentic AI",LinkedIn Learning,"Jul 21, 2025",ecc4f8cdb8d8a0af3e3587f8bfe940c97c73a50deca72279304188e028f9005e,,55,"Anthropic, Claude, AI, Agents, Application, Programming, Interfaces (API)"
8,Learning Kubernetes,LinkedIn Learning,"Jul 20, 2025",9e9010446496f38f2a05668e156edea5e8afc63654e40379b8d9566408c45043,1,28,Kubernetes
9,"DevOps Foundations: Continuous Delivery
/Continuous Integration",LinkedIn Learning,"Jul 16, 2025",f7bb22c2c0dbc59c15e8459628832a3ea28be99c46549e210a9180ba8b0a4606,,,"Continuous Integration and Continuous Delivery (CI/CD) DevOps The CompTIA logo is a registered trademark of CompTIA, Inc. This course is valid for continuing education units toward A+, Network+, Security+, and Cloud+."
10,"AWS API Gateway with HTTP,
Lambda, DynamoDB, and iOS",LinkedIn Learning,"Jul 11, 2025",e70d37f0cafe8215b0a603f724df137427dfe024b46236a51e596655b8dfda47,2,1,"API, Development, REST, APIs, AWS, Lambda"
11,"AWS Certified Cloud Practitioner
(CLF-C02) Cert Prep: 1 Cloud
Concepts",LinkedIn Learning,"Jul 20, 2025",f8516ab967efb579f4ba5205e1dcdfec26c1eeb1d2fe836e7522b506f57a92d9,,55,"Amazon, Web, Services (AWS), Cloud, Computing"
12,MongoDB Essential Training,LinkedIn Learning,"Jul 08, 2025",05067d9ffbe1acc92103ebb46d7a2940efd531ae6d3b5e559ce8aa724da2a2e6,3,49,MongoDB
13,TypeScript Essential Training,LinkedIn Learning,"Jun 25, 2025",ae62321a86cb8a391af54aa71def55ca67390407ed91e08c06924a2c79bebc78,2,18,TypeScript
14,"Express Essentials: Build Powerful
Web Apps with Node.js",LinkedIn Learning,"Jun 27, 2025",b1758b031c4ab641b173d96d7d4536251e815713c93535796e08950b6538ee69,1,59,"Express.js, Web, Application, Development, Node.js"
15,"Next.js: Creating and Hosting a Full-
Stack Site",LinkedIn Learning,"Jun 21, 2025",a86740c29838ab340fae7f881a666d88bf99ca598c36bcce671d4ca247d5ea81,3,54,"Web, Development, Front-End, Development, Next.js"
16,"Building GraphQL Applications in
Laravel",LinkedIn Learning,"Dec 05, 2024",82c64cb0e42918b944984e9daa13be9f88325cdc47535549d5d8840986343fdd,,44,"GraphQL, Laravel"
17,Django Essential Training,LinkedIn Learning,"Nov 19, 2024",914245da3488fe74cc988207eeedb297e566cd0d8727618a3e5083e905ac4fa4,2,6,Django
18,Learning npm: A Package Manager,LinkedIn Learning,"Dec 20, 2024",32db740d99020a763effb933faa2c8bb4d35b96f8fc9fde3284a91596e506b76,,55,"Package, Management npm"
19,Practical GitHub Copilot,LinkedIn Learning,"Dec 30, 2024",c7bf4d6b59f23ca0741527c6241e83b1e64de1c9820ba20000bc0bffc1a7373c,1,0,"GitHub, GitHub, Copilot"
20,Figma Essential Training,LinkedIn Learning,"May 15, 2025",15e5309e9120aab099bd164f5a2f1f8e290866f74755535da1dc6487e19810d4,1,37,Figma (Software)
21,Premiere Pro 2025 Essential Training,LinkedIn Learning,"Mar 14, 2025",5e38de98dd380e18463af59262e59c08ae876b9a22d426e7da0b9b57f4919fc2,5,53,"Non-linear, Editing, Adobe, Premiere, Pro, Video, Editing"
22,Video Editing Fundamentals,LinkedIn Learning,"Mar 23, 2025",f355db89fb691abc0138293d43b50fb689c14775fbff8e0dfaa542e91dc28c8a,,37,"Video, Editing"
23,"WordPress: Custom Post Types and
Taxonomies",LinkedIn Learning,"Dec 18, 2024",2f6601a67d2327b1fc3e3ea54c81dc29299cdc54b447327db40a70caac345d70,2,9,WordPress
24,Agile Foundations,LinkedIn Learning,"Dec 15, 2024",c5189bc0ba85a7e6016059ad1f93e1683f304106df08fa7b006bf1b1647b5f04,,,"Agile Methodologies Agile Project Management IIBA®, the IIBA® logo, BABOK® Guide and Business Analysis Body of Knowledge® are registered trademarks owned by International Institute of Business Analysis."
25,Building RESTful APIs in Laravel,LinkedIn Learning,"Dec 04, 2024",5e6d5ce87925d79cb32aae390f17d62e04850bc609e62be99d04885da01e25f9,1,17,"REST, APIs"
26,Level Up: Python,LinkedIn Learning,"Nov 19, 2024",f10790e4b3c999dbb050501bdfb94bf9737bf3c5bba553a2a30cfe6b167159d1,,57,"Python (Programming, Language)"
27,"Deep Learning Foundations: Natural Language
Processing with TensorFlow",LinkedIn Learning,"Mar 10, 2025",28580b41cff6f96e90dc5aba8174f54ed3a9f6dcccf5db71b9bac4347df66d72,,,"TensorFlow, Natural, Language, Processing (NLP), Deep, Learning"
28,"Fundamentals to Become a Machine
Learning Engineer",LinkedIn Learning,"Feb 27, 2025",6f14c524435962cc935f269cc048c73afdc84b05238a7616884ba9c86f01eb1c,10,22,"Machine, Learning"
29,Securing Software as a Service (SaaS),LinkedIn Learning,"May 25, 2025",5c34cfc123c6e93e529492dfd890e2e04ed4042edf9cf4c548954dac95855bfc,,54,"Cybersecurity, Software as a, Service (SaaS)"
30,Git Essential Training,LinkedIn Learning,"Dec 27, 2024",1e890028719654b52cc922ca5b4464005a4ce2db8d3be0f3561ba01a0fde04b5,1,23,Git
31,"Laravel: Building a CRM with
Filament for Laravel",LinkedIn Learning,"Dec 14, 2024",1f3d00496216aa4d7d8b3c64c5e75cf387ac213564c66114fafd5ae59a661f76,2,51,Laravel
32,Building Angular and Django Apps,LinkedIn Learning,"May 10, 2025",161d0c6326d33016eca724de3899156fe1e22964a825f84ff6ffd94d2c857a7e,1,46,"Web, Application, Development, Angular, Django"
33,Practical GitHub Code Search,LinkedIn Learning,"Jan 01, 2025",559dc4348e18461307db7d714b9bdbd856cc47c2ae25d5864e79979d23783a06,,40,GitHub
34,React Essential Training,LinkedIn Learning,"Dec 08, 2024",e89adcf61aef250dc56a97161dd4b05c2038edced33ea10493b0b816a8b6958c,1,45,"Web, Development, React.js, Front-End, Development"
35,Become an AI Engineer,LinkedIn Learning,"Mar 12, 2025",4268173311ad8a9beade4470e5f3deb30aa070323210fda19a37805c4a43c54d,13,2,"Neural, Networks, Machine, Learning, Python (Programming, Language)"
36,Django Essential Training,LinkedIn Learning,"May 23, 2025",af807f85a18de4d27c1e4e9df3c47b064689149f11df7e2c8975a7eb5998f96a,2,39,"Django, Python (Programming, Language)"
37,Python Essential Training,LinkedIn Learning,"Nov 17, 2024",71ec9ce81c088ab9086beb5510dee27de4eee37e15a4ca1cda0cc4642b0d5633,4,22,"Python (Programming, Language)"
38,Tailwind CSS 4 Essential Training,LinkedIn Learning,"Jun 02, 2025",111a5b9612a3e040339084651ebbf02811648b132ff0451952202e6545295ca0,3,2,"Tailwind, CSS, Cascading, Style, Sheets (CSS)"
39,WordPress: Building a Secure Site,LinkedIn Learning,"Dec 09, 2024",81f10cb5e17866fd99d6f2c15f2c7e7838520e89bff044a6b85ec99cc7ee5e70,,51,WordPress
40,WordPress Essential Training,LinkedIn Learning,"May 14, 2025",8887227bf6002282146185cad81ee8e1da875895293da1822b5ac1f8669597cf,2,4,"Back-End, Web, Development, Wordpress, Development, WordPress"
41,Laravel Essential Training,LinkedIn Learning,"Nov 27, 2024",a62afe7be32b1289dcf55271659faa0b1538345ab4a60ca27b31afd070f72653,3,14,Laravel
42,"Vibe Coding Fundamentals: Tools
and Best Practices",LinkedIn Learning,"Jun 05, 2025",a9342688d934995e9c94a3b50ebc4e9f17aba8c8112495cb2d23ea881ba76122,,37,"Programming, AI, Software, Development, AI, Productivity"
43,Building React and Django Apps,LinkedIn Learning,"May 08, 2025",281091f4ba76c7ce27429f5187229bec80a4b58e2d14001814334fd7f6f87cc0,,56,"React.js, Django"
44,Node.js: Testing and Code Quality,LinkedIn Learning,"Dec 25, 2024",ed0dd5a3a4d81b09e6387e4f321e7d9602ad55868a59edd50c362cdd8589cbde,4,21,Node.js
45,"AI and Generative AI for Video
Content Creation",LinkedIn Learning,"Mar 22, 2025",238455ae1d22c558a63a8391a7f77702158c1c00ad4544167a88540d45ccac3a,1,33,"Artificial, Intelligence for, Design, Video, Creation"
46,Docker for Developers,LinkedIn Learning,"May 30, 2025",37d41dd7fed5ab1665d2b9441537ecc448996fe5b34fb53c2a5491654068edcb,1,15,"Docker, Products"
47,Advanced Laravel,LinkedIn Learning,"Dec 02, 2024",b70e683da49224fac61c5dd4e9c5f442a3b9519a66223f8927958e42c12eef9e,3,14,Laravel
48,Explore React.js Development,LinkedIn Learning,"Feb 11, 2025",c3e08e5bfbfa580df0208ec900acfdb6594ea4f00efe23d0162213b461332c66,20,5,"React.js, JavaScript"
49,"Practical GitHub Project Management and
Collaboration",LinkedIn Learning,"Dec 29, 2024",353a09617244451b65cbf40aa1e8ff6b81d645cdd156bfb319e2be3b3bcece8f,,,"GitHub, Project, Management"
50,Using Python for Automation,LinkedIn Learning,"Nov 20, 2024",56db14e533a3fdcc56dc872cc6501e193d4827f4bfa399a3b55cb71d5fb5d563,1,15,"Process, Automation, Python (Programming, Language)"
51,Livewire Essential Training,LinkedIn Learning,"Dec 11, 2024",2f70f1c665385a9b981264318c8bf3f5ef2a26cc562932cb052bc1d64aa9c85d,,52,"Web, Application, Development, Livewire"
52,Practical GitHub Actions,LinkedIn Learning,"Dec 29, 2024",e40208dfb0d72497af6803f2162dc0a9db0ae89866a4b42ad18a7664d21dba83,1,19,GitHub
53,Introduction to Video Editing,LinkedIn Learning,"Mar 28, 2025",9bb8157b817c27c832ad0d7d645ec3b6759afa19b8aa6b428cd5a70b1ce5c344,2,53,"Video, Editing"
54,Node.js Essential Training,LinkedIn Learning,"Dec 19, 2024",0c86dd65b36e45e63017947a29f1d3c187a7411779ec1fe5c3c815852422a2dc,1,19,Node.js
55,Python Hands-On Practice,LinkedIn Learning,"Apr 29, 2025",7eee12decca890f1993a6cc111c0b6a92d38d970629e240a0a0e35740cdff3df,15,46,"Python (Programming, Language), Coding, Practices"
56,Software Architecture Foundations,LinkedIn Learning,"May 26, 2025",19766d900f59c253530bf7b16568826b9538711edd0684214d0bf0ab99f87dd8,1,36,"Software, Architecture"
57,"Advanced Python: Working with
Databases",LinkedIn Learning,"May 04, 2025",38bc91603bce3d69fc80a77415e1ea2e65870bd43364dcb64090708530d79b43,2,6,"Databases, Python (Programming, Language)"
58,"Building PHP Applications with
Generative AI",LinkedIn Learning,"Dec 28, 2024",0b2ee6d4dbc24eae8a69c3227cceddaa2b4a59303e4e9ae2732e4c834c78ada7,1,23,"Web, Development, PHP, Generative, AI"
59,AWS Essential Training for Developers,LinkedIn Learning,"Jun 06, 2025",21a88cee8041f1495a604a171df1283a7f6ffc7a3443a22f5047c57f61fd6ac1,4,8,"Amazon, Web, Services (AWS)"
60,Leveraging AI in Adobe Premiere Pro,LinkedIn Learning,"Mar 21, 2025",61bc6ccdf7a9524aeac01763f920ce2f04d51325fd32625bf87d94fa3428365f,1,20,"Adobe, Firefly, Artificial, Intelligence for, Design, Adobe, Premiere, Pro"
61,React.js: Building an Interface,LinkedIn Learning,"Jan 04, 2025",2c3409c0ce3da8e46590b436f490ee70b7a0f4ea17fa19741886f0a2d0375ddd,1,40,"React.js, Web, Interface, Design"
62,WordPress: Internationalization,LinkedIn Learning,"Dec 10, 2024",c4f2b4f22693e61f9388f4a4873f42eb13f325d37ee6cdff6e7c19fdd88905b9,1,22,"Internationalization, WordPress"
63,"Advanced Python: Practical
Database Examples",LinkedIn Learning,"May 07, 2025",c444a48013cdb95fa9c3416f1c21aa9777a5472e2620f0a0b47a662429a37afe,1,48,"Database, Development, Python (Programming, Language)"
CSV;

        // Use a temporary stream to parse with fgetcsv (supports multiline quoted fields)
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, $csv);
        rewind($handle);

        $header = fgetcsv($handle, 0, ',', '"');
        if (! $header) {
            fclose($handle);
            return;
        }
        $map = [];
        foreach ($header as $i => $col) {
            $map[strtolower(trim($col))] = $i;
        }

        $sort = 0;
        while (($row = fgetcsv($handle, 0, ',', '"')) !== false) {
            if ($row === [null] || count(array_filter($row, fn($v) => $v !== null && trim((string)$v) !== '')) === 0) {
                continue;
            }

            $name = $row[$map['name']] ?? null;
            $issuer = $row[$map['issuer']] ?? 'LinkedIn Learning';
            $issueDateRaw = $row[$map['issue date']] ?? null;
            $credentialId = $row[$map['credential id']] ?? null;
            $hoursRaw = $row[$map['hours']] ?? null;
            $minutesRaw = $row[$map['minutes']] ?? null;

            $issueDate = null;
            if ($issueDateRaw && ($ts = strtotime($issueDateRaw))) {
                $issueDate = date('Y-m-d', $ts);
            }
            $hours = ($hoursRaw === '' || $hoursRaw === null) ? null : (int) $hoursRaw;
            $minutes = ($minutesRaw === '' || $minutesRaw === null) ? null : (int) $minutesRaw;
            $totalMinutes = null;
            if ($hours !== null || $minutes !== null) {
                $totalMinutes = (int)($hours ?? 0) * 60 + (int)($minutes ?? 0);
                if ($totalMinutes === 0 && $hours === null && $minutes === null) {
                    $totalMinutes = null;
                }
            }

            $org = Organization::firstOrCreate(
                ['name' => $issuer],
                ['website' => null, 'sort_order' => 0]
            );

            Certification::updateOrCreate(
                ['credential_id' => $credentialId ?: null],
                [
                    'user_id' => $user->id,
                    'organization_id' => $org->id,
                    'name' => $name ?: 'Untitled',
                    'issue_date' => $issueDate,
                    'credential_id' => $credentialId ?: null,
                    'total_minutes' => $totalMinutes,
                    'sort_order' => $sort++,
                ]
            );
        }

        fclose($handle);
    }
}
