<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\Tag;
use App\Models\SeoMeta;
use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BizScoopDemoSeeder extends Seeder
{
    // Unsplash images by category keyword (reliable direct URLs)
    private array $images = [
        'business'    => [
            'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&q=80',
            'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&q=80',
            'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=800&q=80',
            'https://images.unsplash.com/photo-1556761175-b413da4baf72?w=800&q=80',
            'https://images.unsplash.com/photo-1444653614773-995cb1ef9efa?w=800&q=80',
        ],
        'startup'     => [
            'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=800&q=80',
            'https://images.unsplash.com/photo-1553877522-43269d4ea984?w=800&q=80',
            'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&q=80',
            'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=80',
            'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=800&q=80',
        ],
        'economy'     => [
            'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&q=80',
            'https://images.unsplash.com/photo-1579532537598-459ecdaf39cc?w=800&q=80',
            'https://images.unsplash.com/photo-1559526324-593bc073d938?w=800&q=80',
            'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80',
            'https://images.unsplash.com/photo-1634128221889-82ed6efebfc3?w=800&q=80',
        ],
        'tech'        => [
            'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&q=80',
            'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=800&q=80',
            'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&q=80',
            'https://images.unsplash.com/photo-1487058792275-0ad4aaf24ca7?w=800&q=80',
            'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=800&q=80',
        ],
        'realestate'  => [
            'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80',
            'https://images.unsplash.com/photo-1582407947304-fd86f28f0da3?w=800&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80',
            'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=800&q=80',
            'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&q=80',
        ],
        'markets'     => [
            'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=800&q=80',
            'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&q=80',
            'https://images.unsplash.com/photo-1535320903710-d993d3d77d29?w=800&q=80',
            'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&q=80',
            'https://images.unsplash.com/photo-1642543492481-44e81e3914a7?w=800&q=80',
        ],
        'leadership'  => [
            'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=800&q=80',
            'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=800&q=80',
            'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&q=80',
            'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&q=80',
            'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=800&q=80',
        ],
        'gcc'         => [
            'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&q=80',
            'https://images.unsplash.com/photo-1518684079-3c830dcef090?w=800&q=80',
            'https://images.unsplash.com/photo-1548266653-c28f5b2c8e38?w=800&q=80',
            'https://images.unsplash.com/photo-1539622106114-e0df812097e6?w=800&q=80',
            'https://images.unsplash.com/photo-1580674684081-7617fbf3d745?w=800&q=80',
        ],
    ];

    public function run(): void
    {
        $author = User::first();

        // ── Tags ──────────────────────────────────────────────────────────
        $tagNames = ['Finance', 'Investment', 'Growth', 'Innovation', 'AI', 'Sustainability',
                     'Leadership', 'Startups', 'Markets', 'GCC', 'Dubai', 'Economy',
                     'Technology', 'Real Estate', 'Business'];
        $tags = [];
        foreach ($tagNames as $tagName) {
            $tags[$tagName] = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
        }

        // ── Categories ────────────────────────────────────────────────────
        $categoriesData = [
            [
                'key'         => 'business',
                'name'        => 'Business',
                'slug'        => 'business',
                'description' => 'In-depth business news, corporate strategies, and market intelligence for professionals.',
                'order'       => 1,
                'tags'        => ['Finance', 'Business', 'Growth'],
            ],
            [
                'key'         => 'startup',
                'name'        => 'Startups',
                'slug'        => 'startups',
                'description' => 'Funding rounds, founder stories, and the latest from the startup ecosystem.',
                'order'       => 2,
                'tags'        => ['Startups', 'Innovation', 'Investment'],
            ],
            [
                'key'         => 'economy',
                'name'        => 'Economy',
                'slug'        => 'economy',
                'description' => 'Macroeconomic trends, GDP data, policy updates, and global economic analysis.',
                'order'       => 3,
                'tags'        => ['Economy', 'Finance', 'Growth'],
            ],
            [
                'key'         => 'tech',
                'name'        => 'Tech',
                'slug'        => 'tech',
                'description' => 'Technology news covering AI, fintech, SaaS, cybersecurity, and digital transformation.',
                'order'       => 4,
                'tags'        => ['Technology', 'AI', 'Innovation'],
            ],
            [
                'key'         => 'realestate',
                'name'        => 'Real Estate',
                'slug'        => 'real-estate',
                'description' => 'Property markets, investment opportunities, and real estate trends across the region.',
                'order'       => 5,
                'tags'        => ['Real Estate', 'Investment', 'Dubai'],
            ],
            [
                'key'         => 'markets',
                'name'        => 'Markets',
                'slug'        => 'markets',
                'description' => 'Stock markets, commodities, forex, and financial market analysis.',
                'order'       => 6,
                'tags'        => ['Markets', 'Finance', 'Investment'],
            ],
            [
                'key'         => 'leadership',
                'name'        => 'Leadership',
                'slug'        => 'leadership',
                'description' => 'Executive insights, management strategies, and leadership stories from top CEOs.',
                'order'       => 7,
                'tags'        => ['Leadership', 'Business', 'Growth'],
            ],
            [
                'key'         => 'gcc',
                'name'        => 'GCC / MENA',
                'slug'        => 'gcc-mena',
                'description' => 'Business and economic news from the Gulf Cooperation Council and MENA region.',
                'order'       => 8,
                'tags'        => ['GCC', 'Dubai', 'Economy'],
            ],
        ];

        // ── Articles per category ─────────────────────────────────────────
        $articlesData = [
            'business' => [
                ['title' => 'Global Corporations Rethink Supply Chain Strategy Amid Trade Uncertainty', 'excerpt' => 'Major multinationals are accelerating diversification efforts as geopolitical tensions reshape global trade corridors.'],
                ['title' => 'Why ESG Reporting Is Now a Business Imperative, Not Just a Trend', 'excerpt' => 'Investors and regulators are demanding greater transparency, pushing companies to integrate sustainability into core operations.'],
                ['title' => 'The Rise of the Chief AI Officer: How Businesses Are Adapting to Machine Intelligence', 'excerpt' => 'A new C-suite role is emerging as companies race to embed artificial intelligence at the highest levels of corporate strategy.'],
                ['title' => 'Family Offices Are the New Power Players in Private Equity', 'excerpt' => 'Ultra-high-net-worth families are bypassing traditional PE funds, directly acquiring and managing businesses with long-term capital.'],
                ['title' => 'Franchise Models Making a Global Comeback as Entrepreneurs Seek Proven Systems', 'excerpt' => 'Post-pandemic recovery has reignited interest in franchise businesses across retail, food, and services.'],
                ['title' => 'Corporate Mergers Hit 5-Year High as Interest Rates Stabilize', 'excerpt' => 'Deal activity is surging as companies take advantage of improved credit conditions to consolidate and expand market share.'],
                ['title' => 'How B2B SaaS Companies Are Reinventing Enterprise Sales in 2025', 'excerpt' => 'Product-led growth strategies are transforming how software companies acquire and retain large enterprise customers.'],
            ],
            'startup' => [
                ['title' => 'MENA Startups Raised $3.2 Billion in 2024 — Here\'s Where the Money Went', 'excerpt' => 'Fintech, healthtech, and climate startups dominated the funding landscape across the Gulf and North Africa.'],
                ['title' => 'Y Combinator\'s Latest Batch Includes Record Number of AI-Native Companies', 'excerpt' => 'Silicon Valley\'s most prestigious accelerator is doubling down on artificial intelligence across every industry vertical.'],
                ['title' => 'From Zero to $100M ARR: The Playbook of Today\'s Fastest-Growing B2B Startups', 'excerpt' => 'Elite founders share what separates sustainable hyper-growth from the illusion of scale.'],
                ['title' => 'Why Bootstrapped Startups Are Outperforming VC-Backed Rivals in Profitability', 'excerpt' => 'Capital efficiency is the new growth metric — and founders who built without outside funding are showing the way.'],
                ['title' => 'Dubai\'s D33 Agenda Creates a $100B Opportunity for Tech Entrepreneurs', 'excerpt' => 'The emirate\'s bold economic blueprint is attracting startup founders from Europe, Asia, and the Americas.'],
                ['title' => 'Climate Tech Startups Are Now Attracting the Largest Seed Rounds in History', 'excerpt' => 'Investors are pouring record capital into early-stage companies building solutions for the energy transition.'],
                ['title' => 'The Unicorn That Wasn\'t: Inside the Collapse of a $1B Valuation Startup', 'excerpt' => 'A deep investigation into how one of the region\'s most hyped companies burned through $400M in less than three years.'],
            ],
            'economy' => [
                ['title' => 'IMF Raises GCC Growth Forecast to 4.2% Amid Oil Boom and Diversification Push', 'excerpt' => 'The Gulf states\' diversification strategies are delivering real results, with non-oil sectors driving most of the expansion.'],
                ['title' => 'Inflation Cools Globally But Structural Price Pressures Persist in Services', 'excerpt' => 'Central banks are cautious about declaring victory as housing and labor costs remain stubbornly elevated.'],
                ['title' => 'The Petrodollar\'s Uncertain Future: What Currency Shifts Mean for Global Trade', 'excerpt' => 'A growing number of oil transactions are settling in currencies other than the US dollar, reshaping decades-old financial flows.'],
                ['title' => 'China\'s Economic Slowdown Is Creating Winners and Losers Across Emerging Markets', 'excerpt' => 'Nations dependent on Chinese demand are feeling pressure, while others are stepping in to capture displaced investment.'],
                ['title' => 'Saudi Arabia\'s Non-Oil GDP Surpasses 50% Milestone for the First Time', 'excerpt' => 'Vision 2030\'s diversification drive is delivering measurable results as tourism, entertainment, and manufacturing sectors grow.'],
                ['title' => 'How Digital Currencies Could Reshape Monetary Policy in the Next Decade', 'excerpt' => 'Central bank digital currencies are moving from concept to implementation, raising profound questions about financial sovereignty.'],
                ['title' => 'The Global Talent Migration Is Reshaping National Economies in Unexpected Ways', 'excerpt' => 'Skilled worker movements between countries are creating new economic hubs while draining others of their most productive citizens.'],
            ],
            'tech' => [
                ['title' => 'OpenAI\'s New Enterprise Product Targets Fortune 500 Companies With Vertical AI', 'excerpt' => 'The AI giant is moving beyond consumer products to capture lucrative business markets with specialized, domain-specific models.'],
                ['title' => 'How Quantum Computing Is About to Disrupt Financial Services', 'excerpt' => 'Banks and trading firms are quietly investing billions to prepare for the post-quantum era before it arrives.'],
                ['title' => 'The Rise of Agentic AI: When Machines Don\'t Just Answer — They Act', 'excerpt' => 'A new generation of autonomous AI agents is automating complex workflows that previously required human decision-making.'],
                ['title' => 'Cybersecurity Spending to Hit $300 Billion by 2026 as Threats Escalate', 'excerpt' => 'Nation-state attacks and ransomware campaigns are forcing organizations to fundamentally rethink their digital defenses.'],
                ['title' => 'Saudi Arabia Commits $40B to AI Infrastructure in Landmark Tech Push', 'excerpt' => 'The Kingdom is positioning itself as a global AI hub, with massive investments in data centers, talent, and research.'],
                ['title' => 'Fintech Is Dead — Long Live Embedded Finance', 'excerpt' => 'The next wave of financial innovation isn\'t coming from banks or fintech apps — it\'s being quietly built into everyday software.'],
                ['title' => 'Apple Vision Pro\'s Enterprise Adoption Signals Spatial Computing\'s Business Future', 'excerpt' => 'Early enterprise adopters are finding genuine productivity use cases in manufacturing, healthcare, and architecture.'],
            ],
            'realestate' => [
                ['title' => 'Dubai Property Prices Rise 22% Annually — And Experts Say the Run Isn\'t Over', 'excerpt' => 'Demand from global investors, expatriates, and high-net-worth individuals continues to outpace available prime inventory.'],
                ['title' => 'Riyadh\'s Office Market Is the World\'s Fastest Growing — Here\'s Why', 'excerpt' => 'Corporate relocations, Vision 2030 projects, and a surge of regional HQs are filling premium commercial space at record pace.'],
                ['title' => 'The $1 Trillion Real Estate Opportunity Hidden in Secondary Cities', 'excerpt' => 'While headlines focus on global capitals, mid-sized cities across emerging markets offer compelling risk-adjusted returns.'],
                ['title' => 'How Fractional Ownership Is Democratizing Access to Premium Real Estate', 'excerpt' => 'New platforms are allowing ordinary investors to own shares in high-value properties previously accessible only to ultra-wealthy buyers.'],
                ['title' => 'Sustainable Buildings Command a 15-30% Premium in Key Global Markets', 'excerpt' => 'Green certification is no longer a differentiator — it\'s becoming a baseline requirement for institutional tenants and investors.'],
                ['title' => 'The NEOM Mega-Project: Progress Report on the World\'s Most Ambitious Real Estate Development', 'excerpt' => 'An on-the-ground look at what\'s actually being built in Saudi Arabia\'s futuristic city of the future.'],
                ['title' => 'Mortgage Rates and Affordability: The Crisis Reshaping Housing Markets Globally', 'excerpt' => 'Higher borrowing costs have frozen first-time buyers out of major markets, fundamentally changing residential real estate dynamics.'],
            ],
            'markets' => [
                ['title' => 'S&P 500 Reaches All-Time High as AI Optimism Fuels Tech Rally', 'excerpt' => 'Benchmark indices are hitting record levels as investors continue to price in the transformative potential of artificial intelligence.'],
                ['title' => 'Gold Surges Past $2,500 as Central Banks Accelerate Reserve Diversification', 'excerpt' => 'The precious metal is benefiting from geopolitical uncertainty and a broad shift away from US dollar-denominated assets.'],
                ['title' => 'Oil Markets Face Structural Uncertainty as EV Adoption Accelerates', 'excerpt' => 'OPEC+ supply management is being tested by a global shift toward electric vehicles and clean energy alternatives.'],
                ['title' => 'The IPO Drought Is Ending: 2025 Listings Set to Raise $200 Billion Globally', 'excerpt' => 'After two years of tepid activity, the primary market is showing strong signs of revival with a robust pipeline of high-quality candidates.'],
                ['title' => 'Emerging Market Bonds Are Offering the Best Risk-Adjusted Returns in a Decade', 'excerpt' => 'Institutional investors are rotating toward EM fixed income as valuations become compelling relative to developed market alternatives.'],
                ['title' => 'The Trillion-Dollar Hedge Fund Industry Is Evolving — Fast', 'excerpt' => 'Quantitative strategies, AI-driven trading, and multi-manager platforms are reshaping how the world\'s elite investors generate returns.'],
                ['title' => 'Crypto Enters Institutional Mainstream as Bitcoin ETFs Attract Record Inflows', 'excerpt' => 'The approval of spot Bitcoin ETFs has opened the floodgates to trillions in traditional institutional capital.'],
            ],
            'leadership' => [
                ['title' => 'The CEO Playbook for Leading Through Permanent Uncertainty', 'excerpt' => 'Today\'s executives face a world of compounding disruptions — and the leaders winning are those who\'ve abandoned the illusion of stability.'],
                ['title' => 'How the World\'s Most Admired CEOs Are Structuring Their Days in 2025', 'excerpt' => 'Exclusive insights into the daily routines, priorities, and decision-making frameworks of elite executives.'],
                ['title' => 'Why Emotional Intelligence Has Become the Most Important Leadership Skill', 'excerpt' => 'Research consistently shows that EQ outperforms IQ in predicting leadership effectiveness across industries and cultures.'],
                ['title' => 'The Board That Saved a Billion-Dollar Company: A Case Study in Governance', 'excerpt' => 'When a major corporation faced existential crisis, decisive board action made the difference between survival and collapse.'],
                ['title' => 'Women Are Redefining What Executive Leadership Looks Like', 'excerpt' => 'As more women ascend to C-suite roles, they are bringing fundamentally different approaches to strategy, culture, and stakeholder management.'],
                ['title' => 'Succession Planning Is Broken — Here\'s How the Best Companies Are Fixing It', 'excerpt' => 'Most organizations fail to identify and develop future leaders until it\'s too late. A new approach is emerging.'],
                ['title' => 'Gen Z Is Entering Leadership Roles Earlier Than Any Generation Before Them', 'excerpt' => 'The oldest Gen Z professionals are already managing teams and driving strategy, bringing radically different expectations to leadership.'],
            ],
            'gcc' => [
                ['title' => 'Saudi Arabia\'s Vision 2030: Midterm Report Shows Ambitious Targets Within Reach', 'excerpt' => 'Halfway through the landmark transformation program, key economic diversification metrics are on track or exceeding projections.'],
                ['title' => 'Dubai Cements Its Position as the World\'s Top City for High-Net-Worth Individuals', 'excerpt' => 'A record 4,500 millionaires relocated to the emirate in 2024, drawn by tax advantages, safety, and lifestyle infrastructure.'],
                ['title' => 'The UAE-India Trade Corridor Is Becoming the World\'s Most Dynamic Economic Relationship', 'excerpt' => 'Bilateral trade is on a trajectory to exceed $100 billion, underpinned by a comprehensive partnership agreement.'],
                ['title' => 'Qatar Post-World Cup: How the Gulf State Is Leveraging Its Global Moment', 'excerpt' => 'The tournament\'s legacy infrastructure and brand recognition are being deployed to attract tourism, business, and investment.'],
                ['title' => 'Kuwait\'s New Economic Blueprint: Breaking 70 Years of Oil Dependency', 'excerpt' => 'The Gulf state is charting an ambitious course toward diversification, with major investments in finance, logistics, and technology.'],
                ['title' => 'NEOM\'s The Line Gets Its First International Anchor Tenants', 'excerpt' => 'Global companies are beginning to commit to Saudi Arabia\'s most ambitious urban development project.'],
                ['title' => 'The GCC Unified Market: How Regional Integration Is Creating a $3 Trillion Economic Zone', 'excerpt' => 'Deeper cooperation between Gulf states is reducing barriers and creating scale that rivals major global economic blocs.'],
            ],
        ];

        foreach ($categoriesData as $catData) {
            $this->command->info("Creating category: {$catData['name']}");

            // Create or update category
            $category = Category::updateOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name'             => ['en' => $catData['name']],
                    'description'      => ['en' => $catData['description']],
                    'slug'             => $catData['slug'],
                    'order'            => $catData['order'],
                    'is_active'        => true,
                    'is_featured'      => true,
                    'show_in_header'   => true,
                    'show_in_homepage' => true,
                ]
            );

            // Attach category SEO
            SeoMeta::updateOrCreate(
                ['seoable_type' => Category::class, 'seoable_id' => $category->id],
                [
                    'meta_title'       => $catData['name'] . ' News | BizScoop',
                    'meta_description' => $catData['description'],
                ]
            );

            // Get image pool for this category
            $imgPool = $this->images[$catData['key']];
            $articles = $articlesData[$catData['key']];

            foreach ($articles as $idx => $article) {
                $slug = Str::slug($article['title']);
                if (Post::where('slug', $slug)->exists()) {
                    continue;
                }

                $publishedAt = Carbon::now()->subDays(rand(1, 90))->subHours(rand(0, 23));
                $isTrending  = $idx < 2;
                $isFeatured  = $idx === 0;

                $post = Post::create([
                    'author_id'      => $author->id,
                    'category_id'    => $category->id,
                    'slug'           => $slug,
                    'status'         => 'published',
                    'type'           => 'article',
                    'published_at'   => $publishedAt,
                    'views'          => rand(500, 15000),
                    'trending_score' => rand(10, 100),
                    'reading_time'   => rand(3, 12),
                    'is_sponsored'   => false,
                    'is_trending'    => $isTrending,
                    'is_featured'    => $isFeatured,
                ]);

                // Translation
                PostTranslation::create([
                    'post_id' => $post->id,
                    'locale'  => 'en',
                    'title'   => $article['title'],
                    'excerpt' => $article['excerpt'],
                    'content' => $this->generateContent($article['title'], $article['excerpt']),
                ]);

                // Attach tags
                $catTagNames = $catData['tags'];
                $postTags = collect($catTagNames)->map(fn($t) => $tags[$t]->id ?? null)->filter();
                $post->tags()->sync($postTags->toArray());

                // SEO Meta
                SeoMeta::create([
                    'seoable_type'     => Post::class,
                    'seoable_id'       => $post->id,
                    'meta_title'       => $article['title'] . ' | BizScoop',
                    'meta_description' => $article['excerpt'],
                ]);

                // Add featured image from Unsplash
                $imgUrl = $imgPool[$idx % count($imgPool)];
                try {
                    $post->addMediaFromUrl($imgUrl)
                         ->withCustomProperties(['alt' => $article['title']])
                         ->toMediaCollection('featured_image');
                } catch (\Exception $e) {
                    $this->command->warn("  Could not download image for: {$article['title']}");
                }

                $this->command->line("  ✓ Post: {$article['title']}");
            }
        }

        $this->command->newLine();
        $this->command->info('✅ BizScoop demo data seeded successfully!');
        $this->command->info('   Categories: 8 | Articles: 56 | Images: downloaded from Unsplash');
    }

    private function generateContent(string $title, string $excerpt): string
    {
        $intro    = $excerpt;
        $sections = [
            "The landscape is shifting faster than most organizations can adapt. Industry leaders who spoke to BizScoop this week described a confluence of factors — regulatory pressure, technological disruption, and evolving consumer expectations — that are forcing a fundamental rethink of established strategies.\n\n\"We're not just adjusting our approach. We're rebuilding from the ground up,\" said one regional CEO who requested anonymity due to ongoing negotiations.",
            "## Key Findings\n\nBizScoop's analysis of the latest data points to several structural trends that are likely to define the sector over the next 18 to 24 months:\n\n- **Capital allocation is shifting** toward higher-quality assets with demonstrable cash flow\n- **Talent competition** has intensified, particularly for roles that require both domain expertise and digital fluency\n- **Regulatory complexity** is increasing across all major markets, raising compliance costs for organizations of all sizes\n- **Technology adoption** is no longer optional — laggards face an accelerating competitive disadvantage",
            "## Regional Perspective\n\nThe GCC states are navigating these changes from a position of relative strength. Sovereign wealth funds continue to deploy capital at scale, while ambitious diversification programs are opening new sectors to private investment. However, execution risk remains elevated, particularly in areas where local expertise is still developing.\n\n\"The opportunity is real, but so are the challenges,\" noted a senior partner at a leading regional consultancy. \"Companies that succeed will be those with the patience to build sustainably and the discipline to avoid chasing short-term metrics at the expense of long-term value.\"",
            "## What's Next\n\nIndustry watchers expect the pace of change to accelerate rather than abate. Several regulatory decisions expected in the coming quarter could significantly alter the competitive dynamics in the sector, while ongoing geopolitical developments continue to create both risk and opportunity for well-positioned organizations.\n\nBizScoop will continue to track these developments and bring you in-depth analysis as the story evolves. Subscribe to our daily newsletter for the latest updates delivered directly to your inbox.",
        ];

        return "**{$intro}**\n\n" . implode("\n\n", $sections);
    }
}
