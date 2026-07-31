<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    /**
     * Download an image from a URL, store it in covers/, return the relative path.
     * Returns null on failure so the page is still created without a cover.
     */
    private function downloadCover(string $url, string $filename): ?string
    {
        try {
            $response = Http::timeout(15)->get($url);
            if (! $response->successful()) {
                return null;
            }
            $path = 'covers/' . $filename . '.jpg';
            Storage::disk('public')->put($path, $response->body());
            return $path;
        } catch (\Throwable) {
            return null;
        }
    }

    public function run(): void
    {
        $admin     = User::where('email', 'admin@cms.test')->first();
        $moderator = User::where('email', 'moderator@cms.test')->first();

        // Curated Unsplash images — one per page, matched to the content topic
        $covers = [
            'Company'           => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&q=80',
            'Products'          => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&q=80',
            'Resources'         => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&q=80',
            'Support'           => 'https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?w=800&q=80',
            'Legal'             => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=800&q=80',
            'About Us'          => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&q=80',
            'Leadership'        => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&q=80',
            'Careers'           => 'https://images.unsplash.com/photo-1551434678-e076c223a692?w=800&q=80',
            'Press'             => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=800&q=80',
            'Platform Overview' => 'https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?w=800&q=80',
            'Pricing'           => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&q=80',
            'Changelog'         => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&q=80',
            'Blog'              => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800&q=80',
            'Documentation'     => 'https://images.unsplash.com/photo-1432821596592-e2c18b78144f?w=800&q=80',
            'Case Studies'      => 'https://images.unsplash.com/photo-1556761175-4b46a572b786?w=800&q=80',
            'Help Center'       => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&q=80',
            'Contact Us'        => 'https://images.unsplash.com/photo-1423666639041-f56000c27a9a?w=800&q=80',
            'Privacy Policy'    => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=800&q=80',
            'Terms of Service'  => 'https://images.unsplash.com/photo-1568992687947-868a62a9f521?w=800&q=80',
        ];

        $pages = [
            // Parent menus
            'Company' => [
                'title'        => 'Company',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $admin->id,
                'body'         => '<h2>About Nexus Technologies</h2><p>Nexus Technologies is a leading enterprise content management platform trusted by over 2,400 organisations worldwide. Explore our story, meet our leadership team, discover career opportunities, and read the latest press coverage.</p><ul><li><strong>About Us</strong> — Our mission, values, and history</li><li><strong>Leadership</strong> — Meet the team behind Nexus</li><li><strong>Careers</strong> — Join us and build the future of content</li><li><strong>Press</strong> — News, media kit, and press enquiries</li></ul>',
            ],
            'Products' => [
                'title'        => 'Products',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $admin->id,
                'body'         => '<h2>The Nexus Product Suite</h2><p>Everything you need to create, manage, and deliver content at scale. From our headless CMS platform to integrations and transparent pricing, we have a solution for every team.</p><ul><li><strong>Platform Overview</strong> — Core features and architecture</li><li><strong>Integrations</strong> — Connect Nexus to your existing tools</li><li><strong>Pricing</strong> — Simple, transparent plans for every team size</li><li><strong>Changelog</strong> — See what we have shipped recently</li></ul>',
            ],
            'Resources' => [
                'title'        => 'Resources',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $moderator->id,
                'body'         => '<h2>Learn and Grow with Nexus</h2><p>Explore our library of resources designed to help content teams, developers, and decision-makers get the most out of Nexus and stay ahead in the world of headless content management.</p><ul><li><strong>Blog</strong> — Insights on content strategy and headless architecture</li><li><strong>Documentation</strong> — Technical guides, API reference, and SDKs</li><li><strong>Case Studies</strong> — Real results from real customers</li><li><strong>Webinars</strong> — Live and on-demand sessions from our experts</li></ul>',
            ],
            'Support' => [
                'title'        => 'Support',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $admin->id,
                'body'         => '<h2>We Are Here to Help</h2><p>Our support team is available around the clock to help you get the most out of Nexus. Whether you need technical assistance, want to check service status, or simply want to get in touch, we have you covered.</p><ul><li><strong>Help Center</strong> — Browse our knowledge base and FAQs</li><li><strong>Contact Us</strong> — Reach our sales and support teams</li><li><strong>System Status</strong> — Real-time platform health and incident history</li></ul>',
            ],
            'Legal' => [
                'title'        => 'Legal',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $admin->id,
                'body'         => '<h2>Legal Information</h2><p>Nexus Technologies is committed to transparency and compliance. This section contains all legal documents governing your use of our platform and services.</p><ul><li><strong>Privacy Policy</strong> — How we collect, use, and protect your data</li><li><strong>Terms of Service</strong> — The rules and conditions for using Nexus</li><li><strong>Cookie Policy</strong> — How we use cookies and similar technologies</li></ul><p>If you have any legal enquiries, please contact <strong>legal@nexus.io</strong>.</p>',
            ],

            // Company
            'About Us' => [
                'title'        => 'About Nexus Technologies',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $admin->id,
                'body'         => '<h2>Who We Are</h2><p>Nexus Technologies is a leading enterprise content management platform trusted by over 2,400 organisations worldwide. Founded in 2016, we set out to solve a simple problem: content management tools were either too complex for small teams or too limited for large enterprises.</p><p>Today, our platform powers everything from startup blogs to Fortune 500 knowledge bases, delivering fast, reliable, and beautifully structured content experiences.</p><h2>Our Mission</h2><p>We believe that great content should be easy to create, manage, and publish — without sacrificing control or performance. Our mission is to give every team the tools they need to communicate clearly and consistently at scale.</p><h2>Our Values</h2><ul><li><strong>Transparency</strong> — We build in the open and communicate honestly with our customers.</li><li><strong>Reliability</strong> — 99.9% uptime is not a goal; it is a baseline.</li><li><strong>Simplicity</strong> — Every feature we ship must make the product easier, not harder.</li></ul>',
            ],
            'Leadership' => [
                'title'        => 'Leadership Team',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $admin->id,
                'body'         => '<h2>Meet the Team</h2><p>Our leadership team brings together decades of experience in enterprise software, content strategy, and product design.</p><h3>Alexandra Chen — Chief Executive Officer</h3><p>Alexandra co-founded Nexus after spending eight years at Salesforce leading product strategy for their CMS division. She holds an MBA from Stanford and is a frequent speaker at SaaStr and Content Marketing World.</p><h3>Marcus Webb — Chief Technology Officer</h3><p>Marcus leads our engineering organisation of 120+ engineers. Previously VP of Engineering at Contentful, he is passionate about distributed systems and developer experience.</p><h3>Priya Nair — Chief Product Officer</h3><p>Priya joined Nexus from Adobe, where she led the Experience Manager product line. She is obsessed with reducing time-to-publish for content teams of all sizes.</p>',
            ],
            'Careers' => [
                'title'        => 'Join Our Team',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $moderator->id,
                'body'         => '<h2>Build the Future of Content</h2><p>We are a remote-first company with team members across 18 countries. We offer competitive salaries, equity, and a culture that values deep work and autonomy.</p><h2>Open Positions</h2><h3>Engineering</h3><ul><li>Senior Backend Engineer (Laravel / PHP)</li><li>Staff Frontend Engineer (React / TypeScript)</li><li>Site Reliability Engineer</li></ul><h3>Product & Design</h3><ul><li>Senior Product Designer</li><li>Product Manager — Platform</li></ul><h3>Go-to-Market</h3><ul><li>Enterprise Account Executive (EMEA)</li><li>Customer Success Manager</li><li>Technical Solutions Engineer</li></ul><p>Don\'t see a role that fits? Send your CV to <strong>careers@nexus.io</strong> — we are always looking for exceptional people.</p>',
            ],
            'Press' => [
                'title'        => 'Press & Media',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $moderator->id,
                'body'         => '<h2>In the News</h2><p>Nexus has been featured in TechCrunch, Forbes, and Wired for our approach to headless content management and our commitment to developer experience.</p><h2>Recent Coverage</h2><ul><li><strong>TechCrunch</strong> — "Nexus raises $42M Series B to take on Contentful and Sanity" (March 2024)</li><li><strong>Forbes</strong> — "The 25 Most Promising Enterprise SaaS Companies of 2024"</li><li><strong>The Verge</strong> — "Why the headless CMS market is heating up again"</li></ul><h2>Press Kit</h2><p>Download our brand assets, executive headshots, and company fact sheet from our press kit. For media enquiries, contact <strong>press@nexus.io</strong>.</p>',
            ],

            // Products
            'Platform Overview' => [
                'title'        => 'The Nexus Platform',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $admin->id,
                'body'         => '<h2>One Platform. Every Content Need.</h2><p>Nexus is a headless CMS built for teams that need speed, flexibility, and reliability. Whether you are managing a marketing site, a developer portal, or a multi-brand content operation, Nexus scales with you.</p><h2>Core Features</h2><h3>Content Modelling</h3><p>Define your own content types with a flexible schema builder. No coding required for editors; full API access for developers.</p><h3>Rich Text Editor</h3><p>Our editor supports structured content, embeds, code blocks, and custom components — all with real-time collaboration.</p><h3>Media Library</h3><p>Centralised asset management with automatic image optimisation, CDN delivery, and smart tagging.</p><h3>Roles & Permissions</h3><p>Granular access control lets you define exactly what each team member can see and do.</p><h3>API-First Architecture</h3><p>Every piece of content is accessible via our REST and GraphQL APIs, with SDKs for JavaScript, Python, PHP, and Ruby.</p>',
            ],
            'Pricing' => [
                'title'        => 'Simple, Transparent Pricing',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $admin->id,
                'body'         => '<h2>Plans for Every Team</h2><p>All plans include a 14-day free trial. No credit card required.</p><h3>Starter — Free</h3><p>Perfect for individuals and small projects. Includes 3 users, 1,000 content entries, and 5GB media storage.</p><h3>Growth — $49/month</h3><p>For growing teams. Includes 10 users, 25,000 content entries, 50GB storage, and priority support.</p><h3>Business — $199/month</h3><p>For established organisations. Includes 50 users, unlimited content entries, 500GB storage, SSO, and a dedicated success manager.</p><h3>Enterprise — Custom</h3><p>For large-scale deployments. Includes custom SLAs, on-premise options, advanced security, and white-glove onboarding. Contact our sales team for a tailored quote.</p>',
            ],
            'Changelog' => [
                'title'        => 'Product Changelog',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $moderator->id,
                'body'         => '<h2>What\'s New in Nexus</h2><h3>v3.4.0 — March 2024</h3><ul><li>New: AI-assisted content suggestions in the rich text editor</li><li>New: Bulk publish and unpublish from the content list</li><li>Improved: 40% faster content delivery API response times</li><li>Fixed: Scheduled publish not triggering in certain timezone configurations</li></ul><h3>v3.3.0 — February 2024</h3><ul><li>New: Webhook support for content lifecycle events</li><li>New: Custom field validation rules</li><li>Improved: Media library search now supports fuzzy matching</li><li>Fixed: Role permissions not refreshing after update without re-login</li></ul><h3>v3.2.0 — January 2024</h3><ul><li>New: GraphQL API (beta)</li><li>New: Content versioning with diff view</li><li>Improved: Editor performance on large documents</li></ul>',
            ],

            // Resources
            'Blog' => [
                'title'        => 'The Nexus Blog',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $moderator->id,
                'body'         => '<h2>Insights for Content Teams</h2><p>The Nexus blog covers content strategy, headless architecture, developer experience, and the future of digital publishing. Written by our team and guest contributors from across the industry.</p><h2>Featured Articles</h2><h3>Why Headless CMS is the Right Choice for Enterprise in 2024</h3><p>Traditional monolithic CMS platforms are struggling to keep up with the demands of modern digital experiences. We explore why more enterprises are making the switch to headless architecture.</p><h3>Content Modelling Best Practices for Large Teams</h3><p>A well-designed content model is the foundation of a scalable CMS implementation. Here are the patterns we have seen work best across hundreds of enterprise deployments.</p><h3>How Meridian Bank Reduced Time-to-Publish by 70%</h3><p>A case study on how one of Europe\'s largest retail banks transformed their content operations with Nexus.</p>',
            ],
            'Documentation' => [
                'title'        => 'Developer Documentation',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $admin->id,
                'body'         => '<h2>Get Started with Nexus</h2><p>Everything you need to integrate Nexus into your application. Our documentation covers the REST API, GraphQL API, webhooks, SDKs, and deployment guides.</p><h2>Quick Start</h2><pre><code>npm install @nexus/sdk\n\nimport { NexusClient } from \'@nexus/sdk\'\n\nconst client = new NexusClient({\n  spaceId: \'your-space-id\',\n  accessToken: \'your-access-token\',\n})\n\nconst entries = await client.getEntries({ contentType: \'blogPost\' })</code></pre><h2>API Reference</h2><p>Our REST API follows standard HTTP conventions and returns JSON. All endpoints require authentication via Bearer token.</p><h2>SDKs</h2><ul><li>JavaScript / TypeScript</li><li>PHP (Laravel & Symfony)</li><li>Python</li><li>Ruby on Rails</li><li>Go</li></ul>',
            ],
            'Case Studies' => [
                'title'        => 'Customer Success Stories',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $moderator->id,
                'body'         => '<h2>Real Results from Real Customers</h2><p>See how organisations across industries are using Nexus to transform their content operations.</p><h3>Meridian Bank — Financial Services</h3><p><strong>Challenge:</strong> Managing regulatory content across 14 markets with strict compliance requirements.<br><strong>Solution:</strong> Nexus\'s role-based permissions and approval workflows.<br><strong>Result:</strong> 70% reduction in time-to-publish; zero compliance incidents in 18 months.</p><h3>Vantage Retail — E-commerce</h3><p><strong>Challenge:</strong> Syndicating product content to 6 storefronts in 4 languages.<br><strong>Solution:</strong> Nexus\'s multi-locale content model and API-first delivery.<br><strong>Result:</strong> 3x faster product launches; 45% reduction in content operations headcount.</p><h3>Clearpath University — Education</h3><p><strong>Challenge:</strong> Empowering 200+ faculty members to publish course content without IT involvement.<br><strong>Solution:</strong> Nexus\'s intuitive editor and granular permissions.<br><strong>Result:</strong> 95% editor adoption rate within 30 days of launch.</p>',
            ],

            // Support
            'Help Center' => [
                'title'        => 'Help Center',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $admin->id,
                'body'         => '<h2>How Can We Help?</h2><p>Browse our knowledge base or contact our support team. We typically respond within 2 business hours on Business and Enterprise plans.</p><h2>Popular Topics</h2><ul><li>Getting started with Nexus</li><li>Inviting team members and managing roles</li><li>Setting up webhooks and integrations</li><li>Migrating content from another CMS</li><li>Configuring custom domains</li><li>Billing and subscription management</li></ul><h2>Contact Support</h2><p>Email: <strong>support@nexus.io</strong><br>Live chat: Available in-app on Business and Enterprise plans<br>Phone: Available on Enterprise plans only</p>',
            ],
            'Contact Us' => [
                'title'        => 'Get in Touch',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $moderator->id,
                'body'         => '<h2>We\'d Love to Hear from You</h2><p>Whether you have a question about our product, want to explore an enterprise partnership, or just want to say hello — our team is here.</p><h2>Sales</h2><p>Interested in Nexus for your organisation? Our sales team will help you find the right plan and answer any questions about enterprise features, security, and compliance.</p><p>Email: <strong>sales@nexus.io</strong><br>Phone: +1 (415) 555-0192</p><h2>Offices</h2><p><strong>San Francisco (HQ)</strong><br>340 Pine Street, Suite 800<br>San Francisco, CA 94104</p><p><strong>London</strong><br>1 Canada Square, Level 39<br>Canary Wharf, London E14 5AB</p><p><strong>Singapore</strong><br>1 Raffles Place, #20-61<br>One Raffles Place, Singapore 048616</p>',
            ],

            // Legal
            'Privacy Policy' => [
                'title'        => 'Privacy Policy',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $admin->id,
                'body'         => '<p><em>Last updated: 1 January 2024</em></p><h2>1. Introduction</h2><p>Nexus Technologies, Inc. ("Nexus", "we", "us", or "our") is committed to protecting your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform and services.</p><h2>2. Information We Collect</h2><p>We collect information you provide directly to us, such as when you create an account, subscribe to a plan, or contact our support team. This may include your name, email address, billing information, and usage data.</p><h2>3. How We Use Your Information</h2><p>We use the information we collect to provide, maintain, and improve our services, process transactions, send transactional and promotional communications, and comply with legal obligations.</p><h2>4. Data Retention</h2><p>We retain your personal data for as long as your account is active or as needed to provide services. You may request deletion of your data at any time by contacting privacy@nexus.io.</p>',
            ],
            'Terms of Service' => [
                'title'        => 'Terms of Service',
                'status'       => 'published',
                'publish_date' => null,
                'created_by'   => $admin->id,
                'body'         => '<p><em>Last updated: 1 January 2024</em></p><h2>1. Acceptance of Terms</h2><p>By accessing or using the Nexus platform, you agree to be bound by these Terms of Service and our Privacy Policy. If you do not agree to these terms, please do not use our services.</p><h2>2. Use of Services</h2><p>You may use Nexus only for lawful purposes and in accordance with these Terms. You agree not to use the platform to store, publish, or transmit any content that is illegal, harmful, or infringes the intellectual property rights of others.</p><h2>3. Subscription and Billing</h2><p>Paid plans are billed monthly or annually in advance. All fees are non-refundable except as required by law. We reserve the right to change our pricing with 30 days\' notice.</p><h2>4. Limitation of Liability</h2><p>To the maximum extent permitted by law, Nexus shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of the platform.</p>',
            ],
        ];

        $arabicPages = [
            'Company' => [
                'title_ar' => 'الشركة',
                'body_ar'  => '<h2>عن شركة نِكسَس للتقنيات</h2><p>نِكسَس منصة رائدة لإدارة محتوى المؤسسات، تثق بها أكثر من 2,400 مؤسسة حول العالم. تعرّف إلى قصتنا وفريق القيادة وفرص العمل وآخر أخبارنا.</p><ul><li><strong>من نحن</strong> — رسالتنا وقيمنا وتاريخنا</li><li><strong>القيادة</strong> — الفريق الذي يقود نِكسَس</li><li><strong>الوظائف</strong> — انضم إلينا وابنِ مستقبل المحتوى</li><li><strong>الصحافة</strong> — الأخبار والملف الإعلامي</li></ul>',
            ],
            'Products' => [
                'title_ar' => 'المنتجات',
                'body_ar'  => '<h2>مجموعة منتجات نِكسَس</h2><p>كل ما تحتاجه لإنشاء المحتوى وإدارته ونشره على نطاق واسع. من منصة إدارة المحتوى عديمة الواجهة إلى التكاملات والأسعار الواضحة، لدينا حل لكل فريق.</p><ul><li><strong>نظرة عامة على المنصة</strong> — الميزات الأساسية والبنية</li><li><strong>التكاملات</strong> — اربط نِكسَس بأدواتك الحالية</li><li><strong>الأسعار</strong> — باقات مرنة لكل حجم فريق</li><li><strong>سجل التغييرات</strong> — أحدث ما أطلقناه</li></ul>',
            ],
            'Resources' => [
                'title_ar' => 'الموارد',
                'body_ar'  => '<h2>تعلّم وتطوّر مع نِكسَس</h2><p>استكشف مكتبتنا من الموارد المصممة لمساعدة فرق المحتوى والمطورين وصناع القرار على تحقيق أفضل النتائج مع نِكسَس.</p><ul><li><strong>المدونة</strong> — رؤى حول استراتيجية المحتوى والبنية عديمة الواجهة</li><li><strong>التوثيق</strong> — أدلة تقنية ومرجع API وحزم SDK</li><li><strong>دراسات الحالة</strong> — نتائج حقيقية من عملاء حقيقيين</li><li><strong>الندوات الإلكترونية</strong> — جلسات مباشرة وعند الطلب</li></ul>',
            ],
            'Support' => [
                'title_ar' => 'الدعم',
                'body_ar'  => '<h2>نحن هنا للمساعدة</h2><p>فريق الدعم لدينا متاح على مدار الساعة لمساعدتك على الاستفادة القصوى من نِكسَس، سواء احتجت إلى مساعدة تقنية أو أردت التحقق من حالة الخدمة.</p><ul><li><strong>مركز المساعدة</strong> — قاعدة المعرفة والأسئلة الشائعة</li><li><strong>اتصل بنا</strong> — تواصل مع المبيعات والدعم</li><li><strong>حالة النظام</strong> — حالة المنصة وسجل الحوادث</li></ul>',
            ],
            'Legal' => [
                'title_ar' => 'القانونية',
                'body_ar'  => '<h2>المعلومات القانونية</h2><p>تلتزم نِكسَس للتقنيات بالشفافية والامتثال. يضم هذا القسم المستندات القانونية المنظمة لاستخدامك لمنصتنا وخدماتنا.</p><ul><li><strong>سياسة الخصوصية</strong> — كيفية جمع بياناتك واستخدامها وحمايتها</li><li><strong>شروط الخدمة</strong> — قواعد وشروط استخدام نِكسَس</li><li><strong>سياسة ملفات تعريف الارتباط</strong> — استخدام ملفات تعريف الارتباط</li></ul><p>للاستفسارات القانونية، تواصل مع <strong>legal@nexus.io</strong>.</p>',
            ],
            'About Us' => [
                'title_ar' => 'عن نِكسَس للتقنيات',
                'body_ar'  => '<h2>من نحن</h2><p>نِكسَس للتقنيات منصة رائدة لإدارة محتوى المؤسسات، تأسست عام 2016 لحل مشكلة تعقيد أدوات المحتوى للفرق الصغيرة ومحدوديتها للمؤسسات الكبيرة.</p><p>اليوم، تشغّل منصتنا تجارب محتوى موثوقة وسريعة ومنظمة، من مدونات الشركات الناشئة إلى قواعد المعرفة لدى أكبر الشركات.</p><h2>رسالتنا</h2><p>نؤمن بأن المحتوى الممتاز يجب أن يكون سهل الإنشاء والإدارة والنشر، من دون التضحية بالتحكم أو الأداء.</p><h2>قيمنا</h2><ul><li><strong>الشفافية</strong> — نتواصل بوضوح وصدق مع عملائنا.</li><li><strong>الموثوقية</strong> — الإتاحة بنسبة 99.9% هي نقطة البداية.</li><li><strong>البساطة</strong> — يجب أن تجعل كل ميزة المنتج أسهل استخداماً.</li></ul>',
            ],
            'Leadership' => [
                'title_ar' => 'فريق القيادة',
                'body_ar'  => '<h2>تعرّف إلى الفريق</h2><p>يجمع فريق القيادة لدينا عقوداً من الخبرة في برمجيات المؤسسات واستراتيجية المحتوى وتصميم المنتجات.</p><h3>ألكسندرا تشين — الرئيسة التنفيذية</h3><p>شاركت ألكسندرا في تأسيس نِكسَس بعد ثمانية أعوام في Salesforce، حيث قادت استراتيجية منتجات قسم إدارة المحتوى.</p><h3>ماركوس ويب — الرئيس التقني</h3><p>يقود ماركوس فريق الهندسة لدينا، ويتمتع بخبرة عميقة في الأنظمة الموزعة وتجربة المطورين.</p><h3>بريا ناير — رئيسة المنتجات</h3><p>انضمت بريا إلى نِكسَس من Adobe، وتركّز على تقليل وقت نشر المحتوى للفرق بمختلف أحجامها.</p>',
            ],
            'Careers' => [
                'title_ar' => 'انضم إلى فريقنا',
                'body_ar'  => '<h2>ابنِ مستقبل المحتوى</h2><p>نحن شركة تعتمد العمل عن بُعد، ويعمل أعضاء فريقنا في 18 دولة. نقدم رواتب تنافسية وملكية وثقافة تقدّر التركيز والاستقلالية.</p><h2>الوظائف الشاغرة</h2><h3>الهندسة</h3><ul><li>مهندس خلفية أول (Laravel / PHP)</li><li>مهندس واجهات أمامية خبير (React / TypeScript)</li><li>مهندس موثوقية المواقع</li></ul><h3>المنتج والتصميم</h3><ul><li>مصمم منتجات أول</li><li>مدير منتجات — المنصة</li></ul><h3>السوق والعملاء</h3><ul><li>مدير حسابات مؤسسات (EMEA)</li><li>مدير نجاح العملاء</li><li>مهندس حلول تقنية</li></ul><p>لا تجد وظيفة مناسبة؟ أرسل سيرتك الذاتية إلى <strong>careers@nexus.io</strong>.</p>',
            ],
            'Press' => [
                'title_ar' => 'الصحافة والإعلام',
                'body_ar'  => '<h2>نِكسَس في الأخبار</h2><p>ظهرت نِكسَس في TechCrunch وForbes وWired تقديراً لنهجنا في إدارة المحتوى عديمة الواجهة والتزامنا بتجربة المطورين.</p><h2>تغطيات حديثة</h2><ul><li><strong>TechCrunch</strong> — جولة تمويل Series B بقيمة 42 مليون دولار</li><li><strong>Forbes</strong> — من أكثر شركات SaaS الواعدة لعام 2024</li><li><strong>The Verge</strong> — لماذا يزداد نشاط سوق CMS عديم الواجهة</li></ul><h2>الملف الصحفي</h2><p>نزّل أصول العلامة التجارية وصور التنفيذيين وملخص الشركة. للاستفسارات الإعلامية: <strong>press@nexus.io</strong>.</p>',
            ],
            'Platform Overview' => [
                'title_ar' => 'منصة نِكسَس',
                'body_ar'  => '<h2>منصة واحدة لكل احتياجات المحتوى</h2><p>نِكسَس نظام إدارة محتوى عديم الواجهة للفرق التي تحتاج إلى السرعة والمرونة والموثوقية، من مواقع التسويق إلى بوابات المطورين والعمليات متعددة العلامات التجارية.</p><h2>الميزات الأساسية</h2><h3>نمذجة المحتوى</h3><p>أنشئ أنواع محتوى خاصة بك باستخدام منشئ مخططات مرن.</p><h3>محرر النص المنسق</h3><p>يدعم المحتوى المنظم والتضمينات وكتل الشيفرة والتعاون الفوري.</p><h3>مكتبة الوسائط</h3><p>إدارة مركزية للأصول مع تحسين تلقائي للصور وتسليم عبر CDN.</p><h3>الأدوار والصلاحيات</h3><p>تحكم دقيق في ما يمكن لكل عضو رؤيته وتنفيذه.</p><h3>بنية API أولاً</h3><p>يتوفر كل المحتوى عبر REST وGraphQL وحزم SDK متعددة.</p>',
            ],
            'Pricing' => [
                'title_ar' => 'أسعار بسيطة وواضحة',
                'body_ar'  => '<h2>باقات لكل فريق</h2><p>تشمل جميع الباقات تجربة مجانية لمدة 14 يوماً، من دون بطاقة ائتمانية.</p><h3>Starter — مجاناً</h3><p>للأفراد والمشاريع الصغيرة: 3 مستخدمين و1,000 إدخال محتوى و5 جيجابايت للوسائط.</p><h3>Growth — 49 دولاراً شهرياً</h3><p>للفرق النامية: 10 مستخدمين و25,000 إدخال و50 جيجابايت ودعم ذو أولوية.</p><h3>Business — 199 دولاراً شهرياً</h3><p>للمؤسسات القائمة: 50 مستخدماً ومحتوى غير محدود وSSO ومدير نجاح مخصص.</p><h3>Enterprise — سعر مخصص</h3><p>لعمليات النشر واسعة النطاق مع اتفاقيات SLA وأمان متقدم وإعداد مخصص.</p>',
            ],
            'Changelog' => [
                'title_ar' => 'سجل تغييرات المنتج',
                'body_ar'  => '<h2>ما الجديد في نِكسَس</h2><h3>الإصدار 3.4.0 — مارس 2024</h3><ul><li>اقتراحات محتوى مدعومة بالذكاء الاصطناعي في المحرر</li><li>نشر وإلغاء نشر جماعي من قائمة المحتوى</li><li>تحسين سرعة استجابة API لتسليم المحتوى بنسبة 40%</li><li>إصلاح النشر المجدول في بعض إعدادات المناطق الزمنية</li></ul><h3>الإصدار 3.3.0 — فبراير 2024</h3><ul><li>دعم Webhooks لأحداث دورة حياة المحتوى</li><li>قواعد تحقق مخصصة للحقول</li><li>تحسين البحث في مكتبة الوسائط</li></ul><h3>الإصدار 3.2.0 — يناير 2024</h3><ul><li>واجهة GraphQL تجريبية</li><li>إصدارات المحتوى مع عرض الفروقات</li><li>تحسين أداء المحرر للمستندات الكبيرة</li></ul>',
            ],
            'Blog' => [
                'title_ar' => 'مدونة نِكسَس',
                'body_ar'  => '<h2>رؤى لفرق المحتوى</h2><p>تغطي مدونة نِكسَس استراتيجية المحتوى والبنية عديمة الواجهة وتجربة المطورين ومستقبل النشر الرقمي، بمقالات من فريقنا وضيوف من القطاع.</p><h2>مقالات مختارة</h2><h3>لماذا يعد CMS عديم الواجهة الخيار المناسب للمؤسسات في 2024</h3><p>نستعرض أسباب انتقال المزيد من المؤسسات من المنصات التقليدية إلى البنية عديمة الواجهة.</p><h3>أفضل ممارسات نمذجة المحتوى للفرق الكبيرة</h3><p>نماذج عملية لبناء هيكل محتوى قابل للتوسع.</p><h3>كيف خفّض Meridian Bank وقت النشر بنسبة 70%</h3><p>دراسة حالة عن تطوير عمليات المحتوى باستخدام نِكسَس.</p>',
            ],
            'Documentation' => [
                'title_ar' => 'توثيق المطورين',
                'body_ar'  => '<h2>ابدأ مع نِكسَس</h2><p>كل ما تحتاجه لدمج نِكسَس في تطبيقك: REST API وGraphQL وWebhooks وحزم SDK وأدلة النشر.</p><h2>بدء سريع</h2><pre><code>npm install @nexus/sdk</code></pre><h2>مرجع API</h2><p>تتبع واجهة REST لدينا معايير HTTP وتعيد بيانات JSON. تتطلب جميع نقاط النهاية المصادقة باستخدام Bearer token.</p><h2>حزم SDK</h2><ul><li>JavaScript / TypeScript</li><li>PHP (Laravel وSymfony)</li><li>Python</li><li>Ruby on Rails</li><li>Go</li></ul>',
            ],
            'Case Studies' => [
                'title_ar' => 'قصص نجاح العملاء',
                'body_ar'  => '<h2>نتائج حقيقية من عملاء حقيقيين</h2><p>اكتشف كيف تستخدم المؤسسات في مختلف القطاعات نِكسَس لتحويل عمليات المحتوى لديها.</p><h3>Meridian Bank — الخدمات المالية</h3><p><strong>التحدي:</strong> إدارة محتوى تنظيمي في 14 سوقاً.<br><strong>الحل:</strong> صلاحيات نِكسَس وسير عمل الموافقات.<br><strong>النتيجة:</strong> خفض وقت النشر بنسبة 70% ومن دون حوادث امتثال خلال 18 شهراً.</p><h3>Vantage Retail — التجارة الإلكترونية</h3><p><strong>النتيجة:</strong> إطلاق المنتجات أسرع بثلاث مرات وخفض فريق عمليات المحتوى بنسبة 45%.</p><h3>Clearpath University — التعليم</h3><p><strong>النتيجة:</strong> نسبة اعتماد للمحررين بلغت 95% خلال 30 يوماً.</p>',
            ],
            'Help Center' => [
                'title_ar' => 'مركز المساعدة',
                'body_ar'  => '<h2>كيف يمكننا مساعدتك؟</h2><p>تصفح قاعدة المعرفة أو تواصل مع فريق الدعم. نستجيب عادة خلال ساعتين عمل في باقات Business وEnterprise.</p><h2>المواضيع الشائعة</h2><ul><li>البدء باستخدام نِكسَس</li><li>دعوة أعضاء الفريق وإدارة الأدوار</li><li>إعداد Webhooks والتكاملات</li><li>ترحيل المحتوى من نظام آخر</li><li>إعداد النطاقات المخصصة</li><li>إدارة الفواتير والاشتراكات</li></ul><h2>تواصل مع الدعم</h2><p>البريد الإلكتروني: <strong>support@nexus.io</strong><br>الدردشة المباشرة متاحة داخل التطبيق لباقات Business وEnterprise.</p>',
            ],
            'Contact Us' => [
                'title_ar' => 'تواصل معنا',
                'body_ar'  => '<h2>يسعدنا أن نسمع منك</h2><p>سواء كان لديك سؤال عن المنتج أو ترغب في شراكة مؤسسية أو تريد إلقاء التحية، فريقنا هنا لمساعدتك.</p><h2>المبيعات</h2><p>سيساعدك فريق المبيعات في اختيار الباقة المناسبة والإجابة عن أسئلة الميزات والأمان والامتثال.</p><p>البريد الإلكتروني: <strong>sales@nexus.io</strong><br>الهاتف: +1 (415) 555-0192</p><h2>مكاتبنا</h2><p><strong>سان فرانسيسكو (المقر الرئيسي)</strong><br>340 Pine Street, Suite 800<br>San Francisco, CA 94104</p><p><strong>لندن</strong><br>1 Canada Square, Level 39<br>Canary Wharf, London E14 5AB</p><p><strong>سنغافورة</strong><br>1 Raffles Place, #20-61<br>One Raffles Place, Singapore 048616</p>',
            ],
            'Privacy Policy' => [
                'title_ar' => 'سياسة الخصوصية',
                'body_ar'  => '<p><em>آخر تحديث: 1 يناير 2024</em></p><h2>1. المقدمة</h2><p>تلتزم شركة نِكسَس للتقنيات بحماية معلوماتك الشخصية. تشرح هذه السياسة كيفية جمع معلوماتك واستخدامها والإفصاح عنها وحمايتها عند استخدام منصتنا وخدماتنا.</p><h2>2. المعلومات التي نجمعها</h2><p>نجمع المعلومات التي تقدمها لنا مباشرة، مثل بيانات الحساب والاشتراك والتواصل مع الدعم، وقد تشمل الاسم والبريد الإلكتروني ومعلومات الفوترة وبيانات الاستخدام.</p><h2>3. كيفية استخدام المعلومات</h2><p>نستخدم المعلومات لتقديم خدماتنا وصيانتها وتحسينها ومعالجة المعاملات وإرسال الرسائل والوفاء بالالتزامات القانونية.</p><h2>4. الاحتفاظ بالبيانات</h2><p>نحتفظ ببياناتك الشخصية ما دام حسابك نشطاً أو حسب الحاجة لتقديم الخدمات. يمكنك طلب حذف بياناتك بالتواصل مع privacy@nexus.io.</p>',
            ],
            'Terms of Service' => [
                'title_ar' => 'شروط الخدمة',
                'body_ar'  => '<p><em>آخر تحديث: 1 يناير 2024</em></p><h2>1. قبول الشروط</h2><p>باستخدام منصة نِكسَس، فإنك توافق على الالتزام بشروط الخدمة وسياسة الخصوصية. إذا لم توافق عليها، يرجى عدم استخدام خدماتنا.</p><h2>2. استخدام الخدمات</h2><p>يجوز لك استخدام نِكسَس لأغراض مشروعة فقط ووفقاً لهذه الشروط. وتوافق على عدم تخزين أو نشر أو إرسال محتوى غير قانوني أو ضار أو ينتهك حقوق الآخرين.</p><h2>3. الاشتراك والفوترة</h2><p>تُفوتر الباقات المدفوعة شهرياً أو سنوياً مقدماً. الرسوم غير قابلة للاسترداد إلا عندما يقتضي القانون ذلك، ويجوز لنا تعديل الأسعار بإشعار مدته 30 يوماً.</p><h2>4. تحديد المسؤولية</h2><p>إلى أقصى حد يسمح به القانون، لا تتحمل نِكسَس مسؤولية الأضرار غير المباشرة أو العرضية أو الخاصة أو التبعية الناتجة عن استخدامك للمنصة.</p>',
            ],
        ];

        foreach ($pages as $menuName => $pageData) {
            $menu = Menu::where('name', $menuName)->first();
            if (! $menu) {
                continue;
            }

            $title      = $pageData['title'];
            $coverUrl   = $covers[$menuName] ?? null;
            $coverPath  = $coverUrl ? $this->downloadCover($coverUrl, Str::slug($menuName)) : null;

            Page::create([
                'title'        => $title,
                'title_ar'     => $arabicPages[$menuName]['title_ar'],
                'slug'         => Str::slug($title),
                'body'         => $pageData['body'],
                'body_ar'      => $arabicPages[$menuName]['body_ar'],
                'cover_image'  => $coverPath,
                'status'       => $pageData['status'],
                'publish_date' => $pageData['publish_date'] ?? null,
                'menu_id'      => $menu->id,
                'created_by'   => $pageData['created_by'],
                'updated_by'   => $pageData['created_by'],
            ]);
        }
    }
}
