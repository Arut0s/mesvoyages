<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of VoyagesControllerTest
 *
 * @author FENOUILLET Paul
 */
class VoyagesControllerTest extends WebTestCase{
    
    public function testAccesPage(){
        $client = static::createClient();
        $client->request('GET','/voyages');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testContenuPage(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/voyages');
        $this->assertSelectorTextContains('h1', 'Mes voyages');
        $this->assertSelectorTextContains('th', 'Ville');
        $this->assertCount(4, $crawler->filter('th'));
        $this->assertSelectortextContains('h5','Gregoirenec');
    }
    
    public function testLinkVille(){
        $client = static::createClient();
        $client->request('GET', '/voyages');
        //clic sur le lien (nom d'une ville)
        $client->clickLink('Gregoirenec');
        //récup résultat du clic
        $response = $client->getResponse();
        //controle si le lien existe
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        //récup de la route et cntrole qu'elle est correct
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/voyages/voyage/12', $uri);
    }
    
    public function testFiltreVille(){
        $client = static::createClient();
        $client->request('GET', '/voyages');
        //simulation soumission formulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Blondel'
        ]);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(1, $crawler->filter('h5'));
        //vérifie si la ville correspond
        $this->assertSelectorTextContains('h5', 'Blondel');
    }
}
