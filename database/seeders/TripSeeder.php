<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $tokyoId = DB::table('cities')->where('name', 'Tokyo')->value('id');
        $newYorkId = DB::table('cities')->where('name', 'New York')->value('id');
        $dubaiId = DB::table('cities')->where('name', 'Dubai')->value('id');
        $delhiId = DB::table('cities')->where('name', 'Delhi')->value('id');
        $londonId = DB::table('cities')->where('name', 'London')->value('id');
        $losAngelesId = DB::table('cities')->where('name', 'Los Angeles')->value('id');
        $sydneyId = DB::table('cities')->where('name', 'Sydney')->value('id');

        DB::table('trips')->insert([
            [
                'title' => 'Découverte de Bali et Lombok',
                'description' => 'Un voyage exceptionnel à la découverte des plus belles îles de l’Indonésie, entre plages paradisiaques, rizières et volcans.',
                'pack_type' => 'premium',
                'destination' => 'Asie',
                'duration' => 12,
                'image' => 'storage/images/trips/bali_lombok.jpg',
                'city_id' => null,
                'activities' => 'Visite des rizières, Randonnée sur le volcan, Plongée sous-marine',
                'included' => 'Transport, Hébergement, Guide touristique'
            ],
            [
                'title' => 'Aventure en Patagonie',
                'description' => 'Explorez la nature sauvage de la Patagonie à travers des randonnées au cœur des glaciers et des montagnes majestueuses.',
                'pack_type' => 'standard',
                'destination' => 'Amérique',
                'duration' => 10,
                'image' => 'storage/images/trips/patagonie.jpg',
                'city_id' => null,
                'activities' => 'Randonnées glaciaires, Observation de la faune, Croisière',
                'included' => 'Hébergement, Transport, Repas'
            ],
            [
                'title' => 'Road Trip en Italie : De Rome à la Toscane',
                'description' => 'Découvrez les merveilles de l’Italie avec ce road trip allant de la Rome historique à la région des vignobles en Toscane.',
                'pack_type' => 'premium',
                'destination' => 'Europe',
                'duration' => 7,
                'image' => 'storage/images/trips/italie_rome.jpg',
                'city_id' => null,
                'activities' => 'Visite de Rome, Dégustation de vin, Tour en voiture',
                'included' => 'Hébergement, Transport, Repas'
            ],
            [
                'title' => 'Safari en Afrique du Sud',
                'description' => 'Partez à l’aventure et vivez une expérience inoubliable au cœur de la faune africaine avec un safari en Afrique du Sud.',
                'pack_type' => 'premium',
                'destination' => 'Afrique',
                'duration' => 9,
                'image' => 'storage/images/trips/safari.jpg',
                'city_id' => null,
                'activities' => 'Safari en Jeep, Observation des animaux, Soirée au camp',
                'included' => 'Hébergement, Repas, Guide'
            ],
            [
                'title' => 'Les Merveilles de l’Australie',
                'description' => 'Découvrez les paysages contrastés de l’Australie, de la Grande Barrière de corail aux étendues désertiques de l’Outback.',
                'pack_type' => 'standard',
                'destination' => 'Australie',
                'duration' => 14,
                'image' => 'storage/images/trips/australie.webp',
                'city_id' => $sydneyId,
                'activities' => 'Plongée dans la Grande Barrière de corail, Randonnée dans l\'Outback, Visite de Sydney',
                'included' => 'Hébergement, Repas, Guide'
            ],
            [
                'title' => 'Découverte de Tokyo',
                'description' => 'Explorez la métropole fascinante de Tokyo, avec ses gratte-ciels, ses temples anciens et sa cuisine délicieuse.',
                'pack_type' => 'standard',
                'destination' => 'Asie',
                'duration' => 7,
                'image' => 'storage/images/trips/tokyo-main.avif',
                'city_id' => $tokyoId,
                'activities' => 'Visite du quartier d\'Akihabara, Randonnée sur le Mont Fuji, Dégustation de sushi',
                'included' => 'Transport, Hébergement, Guide'
            ],
            [
                'title' => 'Aventure en Alaska',
                'description' => 'Découvrez les paysages époustouflants de l\'Alaska avec ses glaciers, montagnes et faune sauvage unique. Une expérience inoubliable pour les amoureux de la nature.',
                'pack_type' => 'premium',
                'destination' => 'Amérique',
                'duration' => 10,
                'image' => 'storage/images/trips/alaska.jpg',
                'city_id' => null,
                'activities' => 'Randonnée glaciaire, Observation de la faune, Croisière',
                'included' => 'Transport, Hébergement, Repas'
            ],
            [
                'title' => 'New York, la ville qui ne dort jamais',
                'description' => 'Découvrez l\'énergie et la grandeur de New York, avec ses gratte-ciels emblématiques, Central Park et Times Square.',
                'pack_type' => 'premium',
                'destination' => 'Amérique',
                'duration' => 7,
                'image' => 'storage/images/trips/newyork.jpeg',
                'city_id' => $newYorkId,
                'activities' => 'Tour en bus de Manhattan, Visite de la Statue de la Liberté, Shopping à Times Square',
                'included' => 'Hébergement, Transport, Guide'
            ],
            [
                'title' => 'Dubaï, la ville du futur',
                'description' => 'Plongez dans l\'opulence et la modernité de Dubaï, où le luxe rencontre l\'innovation et les gratte-ciels côtoient les déserts.',
                'pack_type' => 'standard',
                'destination' => 'Asie',
                'duration' => 5,
                'image' => 'storage/images/trips/dubai.webp',
                'city_id' => $dubaiId,
                'activities' => 'Safari dans le désert, Visite du Burj Khalifa, Croisière sur la Marina',
                'included' => 'Hébergement, Repas, Excursions'
            ],
            [
                'title' => 'Aventure en Delhi',
                'description' => 'Explorez la capitale de l\'Inde avec ses monuments historiques, ses bazars colorés et une culture riche et vibrante.',
                'pack_type' => 'premium',
                'destination' => 'Asie',
                'duration' => 6,
                'image' => 'storage/images/trips/delhi.jpg',
                'city_id' => $delhiId,
                'activities' => 'Visite du Taj Mahal, Tour dans Old Delhi, Shopping à Chandni Chowk',
                'included' => 'Transport, Hébergement, Guide'
            ],
            [
                'title' => 'Londres, entre histoire et modernité',
                'description' => 'Découvrez la capitale britannique avec ses monuments historiques, ses musées et son atmosphère cosmopolite.',
                'pack_type' => 'standard',
                'destination' => 'Europe',
                'duration' => 5,
                'image' => 'storage/images/trips/london.jpg',
                'city_id' => $londonId,
                'activities' => 'Tour en bus de Londres, Visite du British Museum, Croisière sur la Tamise',
                'included' => 'Transport, Hébergement, Guide'
            ],
            [
                'title' => 'Los Angeles, ville des anges',
                'description' => 'Partez à la découverte de Los Angeles, ses plages mythiques, ses stars hollywoodiennes et sa vie trépidante.',
                'pack_type' => 'premium',
                'destination' => 'Amérique',
                'duration' => 7,
                'image' => 'storage/images/trips/losangeles.jpg',
                'city_id' => $losAngelesId,
                'activities' => 'Tour à Hollywood, Visite des plages de Malibu, Shopping à Beverly Hills',
                'included' => 'Transport, Hébergement, Repas'
            ],
            [
                'title' => 'Sydney, l\'aventure Down Under',
                'description' => 'Découvrez les paysages urbains de Sydney, sa plage de Bondi et son opéra emblématique, au cœur de l\'Australie.',
                'pack_type' => 'standard',
                'destination' => 'Australie',
                'duration' => 10,
                'image' => 'storage/images/trips/sydney.jpg',
                'city_id' => $sydneyId,
                'activities' => 'Visite de l\'Opéra de Sydney, Plongée à Bondi Beach, Randonnée dans les Blue Mountains',
                'included' => 'Transport, Hébergement, Guide'
            ],
            [
                'title' => 'Maldives Plage de Rêve',
                'description' => 'Découvrez les plages paradisiaques des Maldives, avec leurs eaux cristallines et sable blanc.',
                'pack_type' => 'standard',
                'destination' => 'Maldives',
                'duration' => 7,
                'image' => 'storage/images/trips/maldives-adventure.jpg',
                'city_id' => null,
                'activities' => 'Snorkeling, Bain de soleil, Excursion en bateau',
                'included' => 'Transport, Hébergement, Activités'
            ],
            [
                'title' => 'Maldives Expérience Luxe',
                'description' => 'Profitez d\'un séjour de luxe aux Maldives, avec des villas sur pilotis et des services exclusifs.',
                'pack_type' => 'premium',
                'destination' => 'Maldives',
                'duration' => 7,
                'image' => 'storage/images/trips/maldives-luxury.jpeg',
                'city_id' => null,
                'activities' => 'Dîner privé, Spa exclusif, Croisière privée',
                'included' => 'Hébergement, Repas, Activités exclusives'
            ],
            [
                'title' => 'Maldives Snorkeling & Plongée',
                'description' => 'Explorez les fonds marins des Maldives et vivez une expérience inoubliable de plongée et de snorkeling.',
                'pack_type' => 'standard',
                'destination' => 'Maldives',
                'duration' => 7,
                'image' => 'storage/images/trips/maldives-honeymoon.jpg',
                'city_id' => null,
                'activities' => 'Plongée, Snorkeling, Croisière',
                'included' => 'Transport, Hébergement, Matériel de plongée'
            ],
        ]);
    }
}
