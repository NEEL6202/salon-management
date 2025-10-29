<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user or create one
        $admin = User::role('super_admin')->first();
        
        if (!$admin) {
            echo "\nNo super admin found. Please create a super admin first.\n";
            return;
        }

        $blogs = [
            [
                'title' => '10 Essential Tips for Growing Your Salon Business',
                'slug' => '10-essential-tips-for-growing-your-salon-business',
                'content' => '<p>Running a successful salon requires more than just great hairstyling skills. Here are 10 proven strategies to help you grow your salon business and attract more clients.</p>
                
                <h3>1. Build a Strong Online Presence</h3>
                <p>In today\'s digital age, having a professional website and active social media presence is crucial. Showcase your best work, share before-and-after photos, and engage with your audience regularly.</p>
                
                <h3>2. Offer Exceptional Customer Service</h3>
                <p>Happy clients become loyal clients. Train your staff to provide personalized attention, remember client preferences, and go the extra mile to exceed expectations.</p>
                
                <h3>3. Implement Online Booking</h3>
                <p>Make it easy for clients to book appointments 24/7 with an online booking system. This convenience can significantly increase your bookings and reduce no-shows.</p>
                
                <h3>4. Create Package Deals</h3>
                <p>Bundle services together at a discounted rate to encourage clients to try multiple services and increase your average ticket value.</p>
                
                <h3>5. Invest in Staff Training</h3>
                <p>Keep your team updated with the latest trends and techniques. Well-trained staff provide better services and attract more clients.</p>
                
                <h3>6. Build Client Loyalty Programs</h3>
                <p>Reward repeat customers with points, discounts, or exclusive perks. This encourages client retention and word-of-mouth referrals.</p>
                
                <h3>7. Leverage Email Marketing</h3>
                <p>Stay connected with clients through regular newsletters featuring tips, promotions, and new services. Email marketing has one of the highest ROIs.</p>
                
                <h3>8. Partner with Local Businesses</h3>
                <p>Collaborate with nearby businesses for cross-promotions. This can help you reach new audiences and build community connections.</p>
                
                <h3>9. Monitor Your Finances</h3>
                <p>Use salon management software to track income, expenses, and profitability. Understanding your numbers helps you make informed business decisions.</p>
                
                <h3>10. Ask for Reviews</h3>
                <p>Positive online reviews build trust and attract new clients. Don\'t be shy about asking satisfied clients to leave reviews on Google or social media.</p>
                
                <p><strong>Conclusion:</strong> Growing a salon business takes time and effort, but with the right strategies, you can build a thriving, profitable salon that stands out from the competition.</p>',
                'excerpt' => 'Discover proven strategies to grow your salon business, attract more clients, and increase profitability with these essential tips.',
                'tags' => 'salon management, business growth, marketing, client retention',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'The Ultimate Guide to Salon Appointment Management',
                'slug' => 'ultimate-guide-salon-appointment-management',
                'content' => '<p>Efficient appointment management is the backbone of any successful salon. Learn how to optimize your booking system and reduce no-shows.</p>
                
                <h3>Why Appointment Management Matters</h3>
                <p>Poor appointment management leads to:</p>
                <ul>
                    <li>Lost revenue from no-shows</li>
                    <li>Overbooking and client frustration</li>
                    <li>Staff downtime</li>
                    <li>Disorganized schedules</li>
                </ul>
                
                <h3>Best Practices for Appointment Scheduling</h3>
                
                <h4>1. Use Online Booking Software</h4>
                <p>Modern salon management software allows clients to book appointments 24/7, automatically syncs with your calendar, and sends automated reminders.</p>
                
                <h4>2. Set Clear Booking Policies</h4>
                <p>Establish and communicate policies for:</p>
                <ul>
                    <li>Cancellation windows (e.g., 24-hour notice)</li>
                    <li>Late arrival policies</li>
                    <li>Deposit requirements for long appointments</li>
                    <li>No-show fees</li>
                </ul>
                
                <h4>3. Send Automated Reminders</h4>
                <p>Reduce no-shows by 30-50% with automated SMS and email reminders sent 24 hours before appointments.</p>
                
                <h4>4. Build in Buffer Time</h4>
                <p>Schedule 10-15 minute buffers between appointments for cleanup, unexpected delays, and client consultations.</p>
                
                <h4>5. Manage Peak Times</h4>
                <p>Identify your busiest hours and days. Consider offering discounts during slower periods to balance your schedule.</p>
                
                <h3>Handling No-Shows</h3>
                <p>When clients don\'t show up:</p>
                <ol>
                    <li>Call immediately to check if they\'re running late</li>
                    <li>Document the no-show</li>
                    <li>Send a follow-up message</li>
                    <li>Consider requiring deposits for repeat offenders</li>
                </ol>
                
                <h3>The Benefits of Good Appointment Management</h3>
                <ul>
                    <li>Increased revenue through better utilization</li>
                    <li>Happier staff with predictable schedules</li>
                    <li>Improved client satisfaction</li>
                    <li>Better work-life balance</li>
                </ul>
                
                <p><strong>Ready to transform your salon operations?</strong> Invest in quality appointment management software and watch your business thrive.</p>',
                'excerpt' => 'Master the art of salon appointment management with our comprehensive guide. Learn how to reduce no-shows and maximize your schedule.',
                'tags' => 'appointments, scheduling, no-shows, salon software',
                'status' => 'published',
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Top Hair Color Trends for 2024',
                'slug' => 'top-hair-color-trends-2024',
                'content' => '<p>Stay ahead of the curve with the hottest hair color trends taking over salons this year. From bold statements to subtle sophistication, we\'ve got you covered.</p>
                
                <h3>1. Expensive Brunette</h3>
                <p>Rich, multi-dimensional brunette shades with warm undertones are dominating 2024. Think chocolate with caramel ribbons and espresso with honey highlights.</p>
                
                <h3>2. Butter Blonde</h3>
                <p>Soft, creamy blonde tones inspired by freshly churned butter. This warm, natural-looking shade flatters most skin tones and requires less maintenance than platinum.</p>
                
                <h3>3. Cherry Cola Red</h3>
                <p>Deep burgundy and cherry tones are making a comeback. This rich, dimensional red works beautifully on all hair types and adds instant drama.</p>
                
                <h3>4. Money Piece Highlights</h3>
                <p>Face-framing highlights that brighten your complexion and add dimension without full highlights. Perfect for low-maintenance clients who want impact.</p>
                
                <h3>5. Rose Gold</h3>
                <p>This romantic, pink-toned shade continues to trend. From subtle rose tints to full metallic rose gold, this versatile color works for everyone.</p>
                
                <h3>6. Mushroom Brown</h3>
                <p>Cool-toned brown with ashy, taupe undertones. This neutral shade is perfect for transitioning from blonde or covering grays naturally.</p>
                
                <h3>7. Cinnamon Copper</h3>
                <p>Warm, spicy copper tones with cinnamon undertones. This trend brings warmth and richness to any base color.</p>
                
                <h3>8. Babylights</h3>
                <p>Ultra-fine, delicate highlights that mimic natural sun-kissed hair. These subtle highlights add dimension without obvious color lines.</p>
                
                <h3>Application Tips for Salon Professionals</h3>
                <ul>
                    <li>Always do a strand test first</li>
                    <li>Use bond-building treatments to maintain hair health</li>
                    <li>Recommend proper home care products</li>
                    <li>Schedule toner touch-ups every 6-8 weeks</li>
                    <li>Educate clients on realistic maintenance</li>
                </ul>
                
                <p><strong>Pro Tip:</strong> Take before and after photos of your color transformations to build your portfolio and attract new clients on social media!</p>',
                'excerpt' => 'Explore the hottest hair color trends of 2024, from expensive brunette to butter blonde. Perfect inspiration for salon professionals.',
                'tags' => 'hair color, trends, balayage, highlights, salon techniques',
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'How to Create an Effective Salon Marketing Strategy',
                'slug' => 'effective-salon-marketing-strategy',
                'content' => '<p>Marketing your salon doesn\'t have to be complicated or expensive. Learn how to create a marketing strategy that attracts clients and builds your brand.</p>
                
                <h3>Understanding Your Target Audience</h3>
                <p>Before you start marketing, know who you\'re trying to reach:</p>
                <ul>
                    <li>Age range and demographics</li>
                    <li>Income level and spending habits</li>
                    <li>Preferred services</li>
                    <li>Where they spend time online</li>
                    <li>What problems they need solved</li>
                </ul>
                
                <h3>Social Media Marketing</h3>
                
                <h4>Instagram</h4>
                <p>Perfect for visual content. Post:</p>
                <ul>
                    <li>Before and after transformations</li>
                    <li>Behind-the-scenes content</li>
                    <li>Client testimonials</li>
                    <li>Product recommendations</li>
                    <li>Team introductions</li>
                </ul>
                
                <h4>Facebook</h4>
                <p>Great for community building and local advertising. Use it for:</p>
                <ul>
                    <li>Event promotions</li>
                    <li>Special offers</li>
                    <li>Client reviews</li>
                    <li>Targeted local ads</li>
                </ul>
                
                <h4>TikTok</h4>
                <p>Reach younger audiences with:</p>
                <ul>
                    <li>Quick styling tutorials</li>
                    <li>Trend participation</li>
                    <li>Day-in-the-life content</li>
                    <li>Hair transformation reveals</li>
                </ul>
                
                <h3>Content Marketing</h3>
                <p>Establish yourself as an expert by creating valuable content:</p>
                <ul>
                    <li>Blog posts about hair care tips</li>
                    <li>Video tutorials</li>
                    <li>Email newsletters</li>
                    <li>Downloadable guides</li>
                </ul>
                
                <h3>Local SEO</h3>
                <p>Optimize for local search:</p>
                <ol>
                    <li>Claim your Google Business Profile</li>
                    <li>Collect and respond to reviews</li>
                    <li>Use local keywords on your website</li>
                    <li>List your business in local directories</li>
                    <li>Create location-specific content</li>
                </ol>
                
                <h3>Referral Programs</h3>
                <p>Turn happy clients into brand ambassadors:</p>
                <ul>
                    <li>Offer referral discounts</li>
                    <li>Create a VIP rewards program</li>
                    <li>Give incentives for social media mentions</li>
                    <li>Host client appreciation events</li>
                </ul>
                
                <h3>Measuring Success</h3>
                <p>Track these metrics:</p>
                <ul>
                    <li>New client acquisition rate</li>
                    <li>Social media engagement</li>
                    <li>Website traffic</li>
                    <li>Email open rates</li>
                    <li>Return on ad spend</li>
                    <li>Client retention rate</li>
                </ul>
                
                <p><strong>Remember:</strong> Consistency is key. Commit to your marketing strategy for at least 6 months before expecting significant results.</p>',
                'excerpt' => 'Learn how to build a powerful salon marketing strategy that attracts new clients and grows your business without breaking the bank.',
                'tags' => 'marketing, social media, seo, client acquisition, branding',
                'status' => 'published',
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => 'Essential Salon Hygiene and Safety Protocols',
                'slug' => 'salon-hygiene-safety-protocols',
                'content' => '<p>Maintaining the highest standards of hygiene and safety is non-negotiable in the salon industry. Here\'s your complete guide to keeping your salon safe and compliant.</p>
                
                <h3>Why Salon Hygiene Matters</h3>
                <p>Proper hygiene protects:</p>
                <ul>
                    <li>Your clients from infections and allergic reactions</li>
                    <li>Your staff from occupational hazards</li>
                    <li>Your business reputation</li>
                    <li>Your license and legal standing</li>
                </ul>
                
                <h3>Daily Cleaning Checklist</h3>
                
                <h4>Morning Routine</h4>
                <ul>
                    <li>Wipe down all surfaces with disinfectant</li>
                    <li>Clean and sanitize all tools</li>
                    <li>Check ventilation systems</li>
                    <li>Restock sanitized tools</li>
                    <li>Inspect workstations for damage</li>
                </ul>
                
                <h4>Between Clients</h4>
                <ul>
                    <li>Sanitize chairs and workstations</li>
                    <li>Change capes and towels</li>
                    <li>Clean and disinfect tools used</li>
                    <li>Sweep hair from floor</li>
                    <li>Wash hands thoroughly</li>
                </ul>
                
                <h4>End of Day</h4>
                <ul>
                    <li>Deep clean all workstations</li>
                    <li>Disinfect all tools and equipment</li>
                    <li>Mop floors with disinfectant</li>
                    <li>Empty and sanitize trash bins</li>
                    <li>Launder all used linens</li>
                </ul>
                
                <h3>Tool Sanitization</h3>
                
                <h4>Three-Step Process</h4>
                <ol>
                    <li><strong>Clean:</strong> Remove visible debris with soap and water</li>
                    <li><strong>Disinfect:</strong> Immerse in EPA-approved disinfectant for required time</li>
                    <li><strong>Store:</strong> Keep in clean, covered containers</li>
                </ol>
                
                <h4>Tools That Need Sanitization</h4>
                <ul>
                    <li>Scissors and shears</li>
                    <li>Combs and brushes</li>
                    <li>Clips and pins</li>
                    <li>Blow dryers and styling tools</li>
                    <li>Nail implements</li>
                </ul>
                
                <h3>Personal Hygiene for Staff</h3>
                <ul>
                    <li>Frequent handwashing (before and after each client)</li>
                    <li>Clean, professional attire</li>
                    <li>Tied-back hair</li>
                    <li>Minimal jewelry</li>
                    <li>Covered cuts or wounds</li>
                    <li>Stay home when sick</li>
                </ul>
                
                <h3>Ventilation and Air Quality</h3>
                <p>Proper ventilation is crucial for:</p>
                <ul>
                    <li>Removing chemical fumes</li>
                    <li>Preventing respiratory issues</li>
                    <li>Reducing odors</li>
                    <li>Improving overall air quality</li>
                </ul>
                
                <h3>Chemical Safety</h3>
                <ul>
                    <li>Store chemicals properly according to MSDS</li>
                    <li>Label all containers clearly</li>
                    <li>Use gloves when handling chemicals</li>
                    <li>Perform patch tests before color services</li>
                    <li>Dispose of chemicals according to local regulations</li>
                </ul>
                
                <h3>Emergency Preparedness</h3>
                <p>Every salon should have:</p>
                <ul>
                    <li>First aid kit</li>
                    <li>Fire extinguisher</li>
                    <li>Emergency contact list</li>
                    <li>Evacuation plan</li>
                    <li>Spill cleanup kit</li>
                </ul>
                
                <p><strong>Remember:</strong> Regular training and consistent enforcement of hygiene protocols protect everyone and demonstrate your professionalism.</p>',
                'excerpt' => 'Comprehensive guide to salon hygiene and safety protocols. Learn how to maintain the highest standards and protect your clients and staff.',
                'tags' => 'hygiene, safety, sanitization, compliance, protocols',
                'status' => 'published',
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => 'Maximizing Retail Sales in Your Salon',
                'slug' => 'maximizing-retail-sales-salon',
                'content' => '<p>Retail products can significantly boost your salon revenue. Learn proven strategies to increase product sales without being pushy.</p>
                
                <h3>Why Retail Matters</h3>
                <p>Product sales can add 10-30% to your revenue with minimal extra effort. Plus, clients who use professional products get better results and become more loyal.</p>
                
                <h3>Creating an Attractive Retail Display</h3>
                <ul>
                    <li>Keep displays clean and organized</li>
                    <li>Use good lighting</li>
                    <li>Create themed product groupings</li>
                    <li>Include testers when possible</li>
                    <li>Place products at eye level</li>
                    <li>Rotate displays monthly</li>
                </ul>
                
                <h3>Staff Training on Product Knowledge</h3>
                <p>Your team should know:</p>
                <ul>
                    <li>Key ingredients and benefits</li>
                    <li>Which products suit different hair types</li>
                    <li>How to use each product</li>
                    <li>Product pricing and promotions</li>
                    <li>How to handle objections</li>
                </ul>
                
                <h3>Natural Sales Opportunities</h3>
                
                <h4>During the Service</h4>
                <p>Explain what products you\'re using and why. Demonstrate techniques clients can replicate at home.</p>
                
                <h4>At the Styling Station</h4>
                <p>"I\'m using this volumizing mousse because it gives great hold without stiffness. Want me to show you how to apply it?"</p>
                
                <h4>At Checkout</h4>
                <p>Recommend the products you used during the service. Make it easy to say yes.</p>
                
                <h3>Effective Recommendation Techniques</h3>
                
                <h4>1. Educate, Don\'t Sell</h4>
                <p>Focus on benefits and solutions, not features and prices.</p>
                
                <h4>2. Use Social Proof</h4>
                <p>"This is our bestseller" or "I use this myself" builds trust.</p>
                
                <h4>3. Offer Bundled Deals</h4>
                <p>Create sets like "Complete Color Care" or "Curl Definition Kit" at a slight discount.</p>
                
                <h4>4. Start Small</h4>
                <p>Recommend travel sizes for clients hesitant about full-size products.</p>
                
                <h3>Incentivizing Your Team</h3>
                <p>Motivate staff with:</p>
                <ul>
                    <li>Commission on retail sales</li>
                    <li>Monthly sales contests</li>
                    <li>Recognition for top sellers</li>
                    <li>Product samples to try at home</li>
                </ul>
                
                <h3>Creating a Loyalty Program</h3>
                <ul>
                    <li>Points for every purchase</li>
                    <li>Birthday discounts</li>
                    <li>VIP early access to new products</li>
                    <li>Free gift with purchase promotions</li>
                </ul>
                
                <h3>Tracking and Measuring Success</h3>
                <p>Monitor:</p>
                <ul>
                    <li>Retail sales per client visit</li>
                    <li>Top-selling products</li>
                    <li>Individual stylist performance</li>
                    <li>Return rate on products</li>
                    <li>Seasonal trends</li>
                </ul>
                
                <p><strong>Pro Tip:</strong> Use your salon management software to track retail sales and identify opportunities for improvement.</p>',
                'excerpt' => 'Boost your salon revenue with effective retail strategies. Learn how to increase product sales naturally and authentically.',
                'tags' => 'retail sales, product recommendations, revenue, customer service',
                'status' => 'published',
                'published_at' => now()->subDays(7),
            ],
        ];

        foreach ($blogs as $blogData) {
            $blogData['author_id'] = $admin->id;
            Blog::create($blogData);
        }

        echo "\n✅ Successfully created " . count($blogs) . " blog posts!\n";
    }
}
