<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\StoresTableSeeder;
use Illuminate\Support\Facades\DB;
use Storage;

class ProductsTableSeeder extends Seeder
{
    protected $httpClient;
    protected $client;

    public function __construct()
    {
        $this->httpClient = new Client();
        $this->client = new Client([
            'base_uri' => 'https://api.pexels.com/v1/',
        ]);
    }
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Products for each store
        $products = [
            1 => [
                ['name' => 'SmartWatch Luxe', 'description' => 'A premium smartwatch with advanced features.', 'price' => 299.99, 'quantity' => 50],
                ['name' => 'Wireless Earbuds Pro', 'description' => 'High-quality sound with noise cancellation.', 'price' => 199.99, 'quantity' => 100],
                ['name' => 'Ultra HD Smart TV', 'description' => 'Experience stunning visuals with our 4K TV.', 'price' => 799.99, 'quantity' => 30],
                ['name' => 'Portable Power Bank', 'description' => 'Keep your devices charged on the go.', 'price' => 49.99, 'quantity' => 150],
                ['name' => 'Bluetooth Home Speaker', 'description' => 'Rich sound in a compact design.', 'price' => 129.99, 'quantity' => 75],
                ['name' => 'Gaming Mouse', 'description' => 'Precision mouse for gamers.', 'price' => 59.99, 'quantity' => 60],
                ['name' => 'Mechanical Keyboard', 'description' => 'Responsive keys for a better typing experience.', 'price' => 89.99, 'quantity' => 40],
                ['name' => '4K HDR Monitor', 'description' => 'Stunning display for your work and play.', 'price' => 499.99, 'quantity' => 20],
                ['name' => 'VR Headset', 'description' => 'Immersive virtual reality experience.', 'price' => 349.99, 'quantity' => 30],
                ['name' => 'Smart Light Bulbs', 'description' => 'Control your lighting with your phone.', 'price' => 29.99, 'quantity' => 150],
                ['name' => 'Fitness Tracker', 'description' => 'Track your workouts and health metrics.', 'price' => 79.99, 'quantity' => 80],
                ['name' => 'Bluetooth Adapter', 'description' => 'Add Bluetooth capability to your PC.', 'price' => 19.99, 'quantity' => 120],
                ['name' => 'Portable SSD', 'description' => 'Fast storage for all your files.', 'price' => 129.99, 'quantity' => 50],
                ['name' => 'Wireless Router', 'description' => 'High-speed internet for your home.', 'price' => 89.99, 'quantity' => 40],
                ['name' => 'Smartphone Gimbal', 'description' => 'Stabilize your videos with ease.', 'price' => 149.99, 'quantity' => 35],
                ['name' => 'Action Camera', 'description' => 'Capture your adventures in high definition.', 'price' => 199.99, 'quantity' => 25],
                ['name' => 'USB-C Hub', 'description' => 'Expand your connectivity options.', 'price' => 39.99, 'quantity' => 70],
                ['name' => 'Desktop Speakers', 'description' => 'High-quality audio for your workspace.', 'price' => 69.99, 'quantity' => 60],
                ['name' => 'Streaming Device', 'description' => 'Watch your favorite shows and movies.', 'price' => 49.99, 'quantity' => 80],
                ['name' => 'Portable Projector', 'description' => 'Project your media anywhere.', 'price' => 299.99, 'quantity' => 20],
                ['name' => 'Wireless Charging Stand', 'description' => 'Convenient, fast charging for your devices.', 'price' => 24.99, 'quantity' => 100],
            ],
            2 => [
                ['name' => 'Culinary Classics Cookbook', 'description' => 'Timeless recipes for every home cook.', 'price' => 29.99, 'quantity' => 200],
                ['name' => 'The Art of Cooking', 'description' => 'A comprehensive guide to culinary arts.', 'price' => 39.99, 'quantity' => 150],
                ['name' => 'Baking with Julia', 'description' => 'Delicious baking recipes from Julia Child.', 'price' => 34.99, 'quantity' => 100],
                ['name' => 'The Flavor Bible', 'description' => 'An essential guide for flavor combinations.', 'price' => 24.99, 'quantity' => 120],
                ['name' => 'Eat, Pray, Love', 'description' => 'A memoir about food, travel, and self-discovery.', 'price' => 16.99, 'quantity' => 80],
                ['name' => 'The Complete Cookbook', 'description' => 'All the recipes you need in one book.', 'price' => 49.99, 'quantity' => 60],
                ['name' => 'Healthy Meal Prep', 'description' => 'Guide to preparing meals in advance.', 'price' => 34.99, 'quantity' => 90],
                ['name' => 'Vegetarian Cooking Made Easy', 'description' => 'Simple vegetarian recipes for everyone.', 'price' => 25.99, 'quantity' => 100],
                ['name' => 'The Essential Baking Cookbook', 'description' => 'Everything you need to bake.', 'price' => 39.99, 'quantity' => 70],
                ['name' => 'Cooking for Two', 'description' => 'Perfect recipes for couples.', 'price' => 29.99, 'quantity' => 80],
                ['name' => 'Gourmet Cooking at Home', 'description' => 'Elevate your home cooking skills.', 'price' => 39.99, 'quantity' => 55],
                ['name' => 'The Art of French Cooking', 'description' => 'Classic French recipes.', 'price' => 45.99, 'quantity' => 40],
                ['name' => 'Family Recipes', 'description' => 'A collection of beloved family recipes.', 'price' => 34.99, 'quantity' => 100],
                ['name' => 'Quick and Easy Meals', 'description' => 'Delicious meals ready in 30 minutes or less.', 'price' => 24.99, 'quantity' => 90],
                ['name' => 'The Joy of Baking', 'description' => 'Baking recipes for every occasion.', 'price' => 29.99, 'quantity' => 85],
                ['name' => 'International Cuisine', 'description' => 'Explore cuisines from around the world.', 'price' => 39.99, 'quantity' => 75],
                ['name' => 'The Spice of Life', 'description' => 'Using spices to enhance your cooking.', 'price' => 19.99, 'quantity' => 120],
                ['name' => 'Cooking with Kids', 'description' => 'Fun recipes to make with your children.', 'price' => 22.99, 'quantity' => 150],
                ['name' => 'Wine and Food Pairing', 'description' => 'Perfect pairings for every meal.', 'price' => 29.99, 'quantity' => 90],
                ['name' => 'Farm to Table', 'description' => 'Fresh recipes using local ingredients.', 'price' => 34.99, 'quantity' => 80],
            ],
            3 => [
                ['name' => 'Herb Infuser', 'description' => 'Infuse your dishes with fresh herbs.', 'price' => 19.99, 'quantity' => 60],
                ['name' => 'Sous Vide Precision Cooker', 'description' => 'Cook to perfection with sous vide technology.', 'price' => 99.99, 'quantity' => 40],
                ['name' => 'Digital Meat Thermometer', 'description' => 'Achieve the perfect cook every time.', 'price' => 29.99, 'quantity' => 100],
                ['name' => 'Smart Coffee Maker', 'description' => 'Brew coffee from your smartphone.', 'price' => 89.99, 'quantity' => 50],
                ['name' => 'Air Fryer Deluxe', 'description' => 'Healthier frying with less oil.', 'price' => 129.99, 'quantity' => 70],
                ['name' => 'Food Processor', 'description' => 'Chop, slice, and puree with ease.', 'price' => 89.99, 'quantity' => 85],
                ['name' => 'Blender with Recipe Book', 'description' => 'Make smoothies and soups.', 'price' => 49.99, 'quantity' => 90],
                ['name' => 'Cast Iron Skillet', 'description' => 'Durable and versatile for all cooking styles.', 'price' => 39.99, 'quantity' => 60],
                ['name' => 'Electric Griddle', 'description' => 'Perfect for pancakes and sandwiches.', 'price' => 59.99, 'quantity' => 80],
                ['name' => 'Ceramic Cookware Set', 'description' => 'Non-stick and easy to clean.', 'price' => 199.99, 'quantity' => 40],
                ['name' => 'Spice Rack Organizer', 'description' => 'Keep your spices tidy and accessible.', 'price' => 24.99, 'quantity' => 100],
                ['name' => 'Stainless Steel Mixing Bowls', 'description' => 'Durable and versatile for all mixing tasks.', 'price' => 34.99, 'quantity' => 70],
                ['name' => 'Kitchen Scale', 'description' => 'Accurate measurements for perfect recipes.', 'price' => 19.99, 'quantity' => 90],
                ['name' => 'Pasta Maker', 'description' => 'Make fresh pasta at home.', 'price' => 79.99, 'quantity' => 50],
                ['name' => 'Salad Spinner', 'description' => 'Dry your greens quickly.', 'price' => 14.99, 'quantity' => 120],
                ['name' => 'Herb Keeper', 'description' => 'Keep herbs fresh longer.', 'price' => 29.99, 'quantity' => 80],
                ['name' => 'Oven Mitts and Pot Holders Set', 'description' => 'Protect your hands while cooking.', 'price' => 19.99, 'quantity' => 100],
                ['name' => 'Non-Stick Baking Sheet', 'description' => 'Perfect for cookies and pastries.', 'price' => 18.99, 'quantity' => 90],
                ['name' => 'Rolling Pin', 'description' => 'Essential for baking enthusiasts.', 'price' => 15.99, 'quantity' => 150],
                ['name' => 'Ice Cream Maker', 'description' => 'Make homemade ice cream.', 'price' => 79.99, 'quantity' => 40],
            ],
            4 => [
                ['name' => 'Eco-Friendly Tote Bag', 'description' => 'Stylish and sustainable.', 'price' => 15.99, 'quantity' => 200],
                ['name' => 'Boho-Chic Maxi Dress', 'description' => 'Comfortable and stylish for any occasion.', 'price' => 49.99, 'quantity' => 100],
                ['name' => 'Vintage-Inspired Sunglasses', 'description' => 'Classic style meets modern UV protection.', 'price' => 29.99, 'quantity' => 150],
                ['name' => 'Athleisure Joggers', 'description' => 'Perfect for workouts or lounging.', 'price' => 39.99, 'quantity' => 120],
                ['name' => 'Statement Necklace Collection', 'description' => 'Unique pieces to elevate any outfit.', 'price' => 24.99, 'quantity' => 80],
                ['name' => 'Cotton Summer Hat', 'description' => 'Protect yourself from the sun in style.', 'price' => 19.99, 'quantity' => 200],
                ['name' => 'Leather Crossbody Bag', 'description' => 'Stylish and practical for daily use.', 'price' => 89.99, 'quantity' => 70],
                ['name' => 'Classic Denim Jacket', 'description' => 'Timeless fashion staple.', 'price' => 59.99, 'quantity' => 60],
                ['name' => 'Stylish Sneakers', 'description' => 'Comfortable and trendy.', 'price' => 79.99, 'quantity' => 50],
                ['name' => 'Wool Scarf', 'description' => 'Warm and fashionable accessory.', 'price' => 29.99, 'quantity' => 100],
                ['name' => 'Casual Graphic Tee', 'description' => 'Express your style effortlessly.', 'price' => 24.99, 'quantity' => 150],
                ['name' => 'Chic Midi Dress', 'description' => 'Perfect for day or night.', 'price' => 45.99, 'quantity' => 80],
                ['name' => 'Stylish Belt', 'description' => 'Complete your outfit.', 'price' => 19.99, 'quantity' => 120],
                ['name' => 'Fashionable Backpack', 'description' => 'Carry your essentials in style.', 'price' => 69.99, 'quantity' => 40],
                ['name' => 'Knit Cardigan', 'description' => 'Cozy and stylish for any season.', 'price' => 39.99, 'quantity' => 60],
                ['name' => 'Elegant Evening Clutch', 'description' => 'Perfect for special occasions.', 'price' => 49.99, 'quantity' => 50],
                ['name' => 'Trendy Ankle Boots', 'description' => 'Versatile footwear for every outfit.', 'price' => 69.99, 'quantity' => 55],
                ['name' => 'Sports Bra', 'description' => 'Comfortable and supportive for workouts.', 'price' => 29.99, 'quantity' => 100],
                ['name' => 'Classic Trousers', 'description' => 'A wardrobe essential for any occasion.', 'price' => 59.99, 'quantity' => 70],
                ['name' => 'Floral Summer Dress', 'description' => 'Lightweight and perfect for warm days.', 'price' => 39.99, 'quantity' => 80],
            ],
            5 => [
                ['name' => 'Noise-Canceling Headphones', 'description' => 'Immerse yourself in sound.', 'price' => 199.99, 'quantity' => 60],
                ['name' => 'Smart Home Hub', 'description' => 'Control all your smart devices in one place.', 'price' => 89.99, 'quantity' => 40],
                ['name' => '4K Action Camera', 'description' => 'Capture every adventure in stunning detail.', 'price' => 149.99, 'quantity' => 30],
                ['name' => 'Wireless Charging Pad', 'description' => 'Convenient, cable-free charging.', 'price' => 29.99, 'quantity' => 80],
                ['name' => 'Fitness Tracker Band', 'description' => 'Track your health and fitness goals.', 'price' => 59.99, 'quantity' => 100],
                ['name' => 'Portable Bluetooth Speaker', 'description' => 'Take your music anywhere.', 'price' => 79.99, 'quantity' => 70],
                ['name' => 'Smart LED Bulbs', 'description' => 'Control your lights from your smartphone.', 'price' => 34.99, 'quantity' => 90],
                ['name' => 'Streaming Device', 'description' => 'Access your favorite streaming services.', 'price' => 49.99, 'quantity' => 100],
                ['name' => 'Smart Lock', 'description' => 'Secure your home with smart technology.', 'price' => 149.99, 'quantity' => 40],
                ['name' => 'Home Security System', 'description' => 'Protect your home with advanced security.', 'price' => 199.99, 'quantity' => 30],
                ['name' => 'Robot Vacuum', 'description' => 'Effortlessly clean your floors.', 'price' => 249.99, 'quantity' => 20],
                ['name' => 'Smart Scale', 'description' => 'Monitor your weight and body composition.', 'price' => 39.99, 'quantity' => 80],
                ['name' => 'Smart Kitchen Assistant', 'description' => 'Get recipe suggestions and cooking tips.', 'price' => 99.99, 'quantity' => 50],
                ['name' => 'Wearable Air Purifier', 'description' => 'Breathe clean air wherever you go.', 'price' => 59.99, 'quantity' => 60],
                ['name' => 'Home Automation Kit', 'description' => 'Start your smart home journey.', 'price' => 199.99, 'quantity' => 40],
                ['name' => 'Smart Plugs', 'description' => 'Control your devices remotely.', 'price' => 29.99, 'quantity' => 100],
                ['name' => 'Smart Thermostat', 'description' => 'Optimize your home heating and cooling.', 'price' => 129.99, 'quantity' => 50],
                ['name' => 'Smart Mirror', 'description' => 'Get information while you get ready.', 'price' => 199.99, 'quantity' => 30],
                ['name' => 'Smart Water Bottle', 'description' => 'Track your hydration levels.', 'price' => 34.99, 'quantity' => 70],
                ['name' => 'Smart Air Quality Monitor', 'description' => 'Track air quality in your home.', 'price' => 49.99, 'quantity' => 60],
            ],
            6 => [
                ['name' => 'Artisan Olive Oil', 'description' => 'Premium quality for your dishes.', 'price' => 29.99, 'quantity' => 60],
                ['name' => 'Handmade Pasta Kit', 'description' => 'Create pasta from scratch.', 'price' => 34.99, 'quantity' => 40],
                ['name' => 'Craft Chocolate Assortment', 'description' => 'Indulge in rich flavors.', 'price' => 19.99, 'quantity' => 70],
                ['name' => 'Organic Tea Sampler', 'description' => 'A selection of fine teas.', 'price' => 24.99, 'quantity' => 80],
                ['name' => 'Exotic Fruit Basket', 'description' => 'A delightful assortment of exotic fruits.', 'price' => 49.99, 'quantity' => 30],
                ['name' => 'Gourmet Coffee Beans', 'description' => 'Freshly roasted coffee for the perfect brew.', 'price' => 19.99, 'quantity' => 100],
                ['name' => 'Artisanal Cheese Board', 'description' => 'A selection of gourmet cheeses.', 'price' => 59.99, 'quantity' => 40],
                ['name' => 'Home Brewing Kit', 'description' => 'Brew your own beer at home.', 'price' => 89.99, 'quantity' => 20],
                ['name' => 'Culinary Herb Garden Kit', 'description' => 'Grow your own herbs at home.', 'price' => 29.99, 'quantity' => 50],
                ['name' => 'Gourmet Chocolate Fondue Set', 'description' => 'Delicious chocolate fondue experience.', 'price' => 39.99, 'quantity' => 60],
                ['name' => 'Cookware Set', 'description' => 'Essential pots and pans for every kitchen.', 'price' => 199.99, 'quantity' => 25],
                ['name' => 'Bamboo Cutting Board Set', 'description' => 'Durable and eco-friendly cutting boards.', 'price' => 24.99, 'quantity' => 80],
                ['name' => 'Non-Stick Baking Mat', 'description' => 'Perfect for all your baking needs.', 'price' => 15.99, 'quantity' => 90],
                ['name' => 'Silicone Baking Molds', 'description' => 'Versatile molds for baking and freezing.', 'price' => 19.99, 'quantity' => 75],
                ['name' => 'Food Dehydrator', 'description' => 'Preserve fruits and vegetables easily.', 'price' => 79.99, 'quantity' => 40],
                ['name' => 'Fermentation Kit', 'description' => 'Make your own fermented foods.', 'price' => 39.99, 'quantity' => 30],
                ['name' => 'Flavor Infuser Water Bottle', 'description' => 'Infuse water with fruits and herbs.', 'price' => 24.99, 'quantity' => 100],
                ['name' => 'Grill Tool Set', 'description' => 'Everything you need for outdoor grilling.', 'price' => 39.99, 'quantity' => 50],
                ['name' => 'Precision Kitchen Thermometer', 'description' => 'Ensure your food is cooked perfectly.', 'price' => 19.99, 'quantity' => 70],
            ],
            7 => [
            ['name' => 'Smart Thermostat', 'description' => 'Control your home temperature remotely.', 'price' => 99.99, 'quantity' => 50],
            ['name' => 'Wireless Security Camera', 'description' => 'Keep an eye on your home from anywhere.', 'price' => 129.99, 'quantity' => 40],
            ['name' => 'LED Strip Lights', 'description' => 'Create ambiance with smart lighting.', 'price' => 29.99, 'quantity' => 100],
            ['name' => 'Portable Bluetooth Projector', 'description' => 'Project your media anywhere.', 'price' => 199.99, 'quantity' => 30],
            ['name' => 'Fitness Smart Scale', 'description' => 'Track your weight and health metrics.', 'price' => 49.99, 'quantity' => 80],
            ['name' => 'Smart Smoke Detector', 'description' => 'Stay safe with smart alerts.', 'price' => 59.99, 'quantity' => 60],
            ['name' => 'Smart Doorbell', 'description' => 'See who’s at your door from your phone.', 'price' => 99.99, 'quantity' => 40],
            ['name' => 'Home Automation Hub', 'description' => 'Control all your smart devices from one place.', 'price' => 129.99, 'quantity' => 30],
            ['name' => 'Smart Water Leak Detector', 'description' => 'Prevent water damage with alerts.', 'price' => 39.99, 'quantity' => 70],
            ['name' => 'Smart Light Switch', 'description' => 'Control your lights with voice commands.', 'price' => 24.99, 'quantity' => 80],
            ['name' => 'Smart Plug', 'description' => 'Turn any device into a smart device.', 'price' => 19.99, 'quantity' => 100],
            ['name' => 'Home Security System', 'description' => 'Comprehensive protection for your home.', 'price' => 299.99, 'quantity' => 15],
            ['name' => 'Smart Air Purifier', 'description' => 'Breathe cleaner air with smart technology.', 'price' => 199.99, 'quantity' => 20],
            ['name' => 'Smart Irrigation System', 'description' => 'Automate your garden watering.', 'price' => 139.99, 'quantity' => 25],
            ['name' => 'Smart Garage Door Opener', 'description' => 'Control your garage door remotely.', 'price' => 89.99, 'quantity' => 40],
            ['name' => 'Smart Refrigerator', 'description' => 'Keep track of your groceries with a smart fridge.', 'price' => 1999.99, 'quantity' => 5],
            ['name' => 'Smart Coffee Maker', 'description' => 'Brew coffee from your smartphone.', 'price' => 89.99, 'quantity' => 30],
            ['name' => 'Smart Mirror', 'description' => 'Get information and news while you prepare.', 'price' => 149.99, 'quantity' => 20],
            ['name' => 'Smart Pool Monitor', 'description' => 'Keep your pool clean and safe.', 'price' => 249.99, 'quantity' => 10],
        ],
            8 => [
                ['name' => 'Recipe Journal', 'description' => 'Document your favorite recipes.', 'price' => 14.99, 'quantity' => 200],
                ['name' => 'Gourmet Snack Cookbook', 'description' => 'Snacks that impress.', 'price' => 24.99, 'quantity' => 150],
                ['name' => 'Kids\' Cooking Book', 'description' => 'Fun recipes for little chefs.', 'price' => 19.99, 'quantity' => 100],
                ['name' => 'Healthy Eating Guide', 'description' => 'Tips and recipes for a healthy lifestyle.', 'price' => 29.99, 'quantity' => 120],
                ['name' => 'Food Photography Essentials', 'description' => 'Capture your culinary creations beautifully.', 'price' => 34.99, 'quantity' => 80],
                ['name' => 'Meal Planning Guide', 'description' => 'Organize your meals for the week.', 'price' => 19.99, 'quantity' => 90],
                ['name' => 'Food Safety Handbook', 'description' => 'Ensure safe food handling and storage.', 'price' => 14.99, 'quantity' => 150],
                ['name' => 'Vegetarian Recipe Book', 'description' => 'Delicious vegetarian recipes for everyone.', 'price' => 29.99, 'quantity' => 80],
                ['name' => 'Slow Cooker Recipe Book', 'description' => 'Easy and delicious slow cooker meals.', 'price' => 24.99, 'quantity' => 70],
                ['name' => 'Healthy Smoothies Guide', 'description' => 'Nutritious smoothies for any time of day.', 'price' => 19.99, 'quantity' => 100],
                ['name' => 'Family Meal Recipes', 'description' => 'Wholesome meals for the whole family.', 'price' => 34.99, 'quantity' => 60],
                ['name' => 'Culinary Skills Workbook', 'description' => 'Enhance your cooking skills with practical tips.', 'price' => 22.99, 'quantity' => 90],
                ['name' => 'Fermentation Recipes', 'description' => 'Learn to ferment your own foods.', 'price' => 29.99, 'quantity' => 50],
                ['name' => 'Art of Plating', 'description' => 'Elevate your food presentation.', 'price' => 39.99, 'quantity' => 40],
                ['name' => 'Farmers Market Cookbook', 'description' => 'Recipes based on seasonal ingredients.', 'price' => 34.99, 'quantity' => 70],
                ['name' => 'Baking for Beginners', 'description' => 'Simple recipes for novice bakers.', 'price' => 19.99, 'quantity' => 90],
                ['name' => 'Cooking for Health', 'description' => 'Recipes for a healthier lifestyle.', 'price' => 29.99, 'quantity' => 80],
                ['name' => 'Gourmet Appetizers Cookbook', 'description' => 'Impressive appetizers for any occasion.', 'price' => 34.99, 'quantity' => 60],
                ['name' => 'Dessert Cookbook', 'description' => 'Delicious desserts for sweet lovers.', 'price' => 29.99, 'quantity' => 80],
            ],
            9 => [
                ['name' => 'Sleek Leather Backpack', 'description' => 'Stylish and functional.', 'price' => 89.99, 'quantity' => 70],
                ['name' => 'Tailored Blazer', 'description' => 'Elevate your wardrobe with this staple.', 'price' => 129.99, 'quantity' => 50],
                ['name' => 'Chic Ankle Boots', 'description' => 'Perfect for any outfit.', 'price' => 69.99, 'quantity' => 100],
                ['name' => 'Flowy Midi Skirt', 'description' => 'Comfortable and chic.', 'price' => 49.99, 'quantity' => 80],
                ['name' => 'Graphic Tee Collection', 'description' => 'Express yourself with style.', 'price' => 24.99, 'quantity' => 150],
                ['name' => 'Stylish Handbag', 'description' => 'Accessorize your outfit.', 'price' => 79.99, 'quantity' => 60],
                ['name' => 'Classic White Sneakers', 'description' => 'Timeless footwear for every look.', 'price' => 49.99, 'quantity' => 80],
                ['name' => 'Trendy Sunglasses', 'description' => 'Protect your eyes in style.', 'price' => 29.99, 'quantity' => 100],
                ['name' => 'Casual Chinos', 'description' => 'Comfortable and versatile pants.', 'price' => 39.99, 'quantity' => 90],
                ['name' => 'Wool Peacoat', 'description' => 'Stay warm and stylish.', 'price' => 129.99, 'quantity' => 40],
                ['name' => 'Stylish Fedora Hat', 'description' => 'Complete your outfit with flair.', 'price' => 34.99, 'quantity' => 50],
                ['name' => 'Classic Denim Jeans', 'description' => 'A wardrobe essential.', 'price' => 59.99, 'quantity' => 75],
                ['name' => 'Leather Wallet', 'description' => 'Durable and stylish.', 'price' => 39.99, 'quantity' => 90],
                ['name' => 'Weekend Duffle Bag', 'description' => 'Perfect for short trips.', 'price' => 69.99, 'quantity' => 60],
                ['name' => 'Silk Scarf', 'description' => 'Add elegance to any outfit.', 'price' => 24.99, 'quantity' => 100],
                ['name' => 'Athletic Shorts', 'description' => 'Perfect for workouts or lounging.', 'price' => 29.99, 'quantity' => 80],
                ['name' => 'Casual Button-Down Shirt', 'description' => 'Versatile for both casual and formal settings.', 'price' => 49.99, 'quantity' => 70],
                ['name' => 'Fashionable Raincoat', 'description' => 'Stay dry in style.', 'price' => 79.99, 'quantity' => 30],
                ['name' => 'Cute Baby Onesies', 'description' => 'Soft and comfortable for your little one.', 'price' => 19.99, 'quantity' => 100],
                ['name' => 'Kids’ Backpack', 'description' => 'Fun designs for school or play.', 'price' => 29.99, 'quantity' => 100],
                ['name' => 'Stylish Swimwear', 'description' => 'Perfect for the beach or pool.', 'price' => 39.99, 'quantity' => 70],
            ],
            10 => [
                ['name' => 'The Joy of Cooking', 'description' => 'A classic cookbook for every kitchen.', 'price' => 39.99, 'quantity' => 100],
                ['name' => 'Salt, Fat, Acid, Heat', 'description' => 'Master the elements of good cooking.', 'price' => 29.99, 'quantity' => 80],
                ['name' => 'The Food Lab', 'description' => 'The science of cooking explained.', 'price' => 49.99, 'quantity' => 60],
                ['name' => 'Plenty: Vibrant Vegetable Recipes', 'description' => 'Delicious vegetarian dishes.', 'price' => 34.99, 'quantity' => 70],
                ['name' => 'The Complete Baking Book', 'description' => 'Everything you need to know about baking.', 'price' => 24.99, 'quantity' => 90],
                ['name' => 'Mastering the Art of French Cooking', 'description' => 'A comprehensive guide to French cuisine.', 'price' => 49.99, 'quantity' => 50],
                ['name' => 'Keto Diet Cookbook', 'description' => 'Recipes for a low-carb lifestyle.', 'price' => 29.99, 'quantity' => 80],
                ['name' => 'Vegetable Gardening for Beginners', 'description' => 'Grow your own vegetables at home.', 'price' => 19.99, 'quantity' => 100],
                ['name' => 'The Complete Guide to Spices', 'description' => 'Unlock the secrets of flavor.', 'price' => 34.99, 'quantity' => 60],
                ['name' => 'The Art of Simple Food', 'description' => 'Simple recipes for everyday cooking.', 'price' => 24.99, 'quantity' => 90],
                ['name' => 'Canning and Preserving', 'description' => 'Preserve your harvest for the future.', 'price' => 29.99, 'quantity' => 70],
                ['name' => 'The Mediterranean Table', 'description' => 'Healthy and delicious Mediterranean recipes.', 'price' => 39.99, 'quantity' => 60],
                ['name' => 'Instant Pot Cookbook', 'description' => 'Quick and easy recipes for busy cooks.', 'price' => 29.99, 'quantity' => 80],
                ['name' => 'The Complete Book of Pickling', 'description' => 'Create your own pickles and preserves.', 'price' => 24.99, 'quantity' => 90],
                ['name' => 'Comfort Food Classics', 'description' => 'Hearty meals that warm the heart.', 'price' => 34.99, 'quantity' => 70],
                ['name' => 'The Essential Pizza Cookbook', 'description' => 'Everything you need to make perfect pizza at home.', 'price' => 19.99, 'quantity' => 100],
                ['name' => 'Slow Cooker Recipes for Families', 'description' => 'Easy meals for busy families.', 'price' => 29.99, 'quantity' => 80],
                ['name' => 'The Art of Stir-Frying', 'description' => 'Master the art of quick cooking.', 'price' => 19.99, 'quantity' => 90],
                ['name' => 'The Complete Guide to Sushi', 'description' => 'Learn to make sushi at home.', 'price' => 39.99, 'quantity' => 60],
                ['name' => 'Gourmet Burger Cookbook', 'description' => 'Create gourmet burgers at home.', 'price' => 29.99, 'quantity' => 70],
            ],
        ];
        foreach ($products as $storeId => $items) {
            foreach ($items as $item) {
                $product = Product::create([
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'details' => json_encode(['color' => 'various', 'size' => 'various']),
                    'store_id' => $storeId,
                ]);
                $imagePath = $this->fetchImage($item['name']);
                DB::table('product_images')->insert([
                    'path' => $imagePath,
                    'is_main' => true,
                    'product_id' => $product->id,
                ]);
            }

        }

    }
    protected function fetchImage($productName)
    {
        $apiKey = '48231768-ac9eeecee0143eefae97e14bf';
        $query = urlencode("{$productName} product");
        $response = $this->httpClient->get("https://pixabay.com/api/?key={$apiKey}&q={$query}");
        $data = json_decode($response->getBody(), true);
        if (isset($data['hits'][0]['webformatURL'])) {
            $imageUrl = $data['hits'][0]['webformatURL'];
            $imageContent = file_get_contents($imageUrl);
            $extension = pathinfo($imageUrl, PATHINFO_EXTENSION);
            $filename = strtolower(str_replace(' ', '_', $productName)) . '.' . $extension;
            $path = 'images/products/' . $filename;
            Storage::disk('public')->put($path, $imageContent);
            return "storage/images/products/{$filename}";
        }
        return 'storage/images/products/main-product.png';
    }
}
