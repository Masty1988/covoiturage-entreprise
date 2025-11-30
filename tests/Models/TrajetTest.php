<?php
namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Trajet;

/**
 * Tests pour le model Trajet
 */
class TrajetTest extends TestCase
{
    private $trajetModel;
    private $testTrajetId;

    protected function setUp(): void
    {
        $this->trajetModel = new Trajet();
    }

    protected function tearDown(): void
    {
        // Nettoie les donnees de test
        if ($this->testTrajetId) {
            $this->trajetModel->delete($this->testTrajetId);
        }
    }

    /**
     * Test creation d'un trajet
     */
    public function testCreate()
    {
        $data = [
            'conducteur_id' => 2, // Alexandre Martin
            'agence_depart_id' => 1,
            'agence_arrivee_id' => 2,
            'date_heure_depart' => date('Y-m-d H:i:s', strtotime('+1 day')),
            'date_heure_arrivee' => date('Y-m-d H:i:s', strtotime('+1 day +2 hours')),
            'places_totales' => 4
        ];

        $result = $this->trajetModel->create($data);
        $this->assertTrue($result);

        // Trouve le trajet cree
        $trajets = $this->trajetModel->all();
        $found = null;
        foreach ($trajets as $trajet) {
            if ($trajet['conducteur_id'] == $data['conducteur_id'] &&
                $trajet['agence_depart_id'] == $data['agence_depart_id'] &&
                $trajet['places_totales'] == $data['places_totales']) {
                $found = $trajet;
            }
        }

        $this->assertNotNull($found);
        $this->assertEquals(4, $found['places_disponibles']);
        $this->testTrajetId = $found['id'];
    }

    /**
     * Test mise a jour d'un trajet
     */
    public function testUpdate()
    {
        // Cree un trajet de test
        $data = [
            'conducteur_id' => 2,
            'agence_depart_id' => 1,
            'agence_arrivee_id' => 3,
            'date_heure_depart' => date('Y-m-d H:i:s', strtotime('+2 days')),
            'date_heure_arrivee' => date('Y-m-d H:i:s', strtotime('+2 days +1 hour')),
            'places_totales' => 3
        ];

        $this->trajetModel->create($data);

        // Trouve le trajet
        $trajets = $this->trajetModel->all();
        $trajet = null;
        foreach ($trajets as $t) {
            if ($t['conducteur_id'] == 2 && $t['agence_arrivee_id'] == 3 && $t['places_totales'] == 3) {
                $trajet = $t;
            }
        }
        $this->testTrajetId = $trajet['id'];

        // Met a jour
        $newData = [
            'agence_depart_id' => 1,
            'agence_arrivee_id' => 4,
            'date_heure_depart' => $data['date_heure_depart'],
            'date_heure_arrivee' => $data['date_heure_arrivee'],
            'places_totales' => 5,
            'places_disponibles' => 5
        ];

        $result = $this->trajetModel->update($trajet['id'], $newData);
        $this->assertTrue($result);

        // Verifie
        $updated = $this->trajetModel->find($trajet['id']);
        $this->assertEquals(5, $updated['places_totales']);
        $this->assertEquals(4, $updated['agence_arrivee_id']);
    }

    /**
     * Test suppression d'un trajet
     */
    public function testDelete()
    {
        // Cree un trajet de test
        $data = [
            'conducteur_id' => 3, // Sophie Dubois
            'agence_depart_id' => 2,
            'agence_arrivee_id' => 5,
            'date_heure_depart' => date('Y-m-d H:i:s', strtotime('+3 days')),
            'date_heure_arrivee' => date('Y-m-d H:i:s', strtotime('+3 days +3 hours')),
            'places_totales' => 2
        ];

        $this->trajetModel->create($data);

        // Trouve le trajet
        $trajets = $this->trajetModel->all();
        $trajet = null;
        foreach ($trajets as $t) {
            if ($t['conducteur_id'] == 3 && $t['agence_arrivee_id'] == 5) {
                $trajet = $t;
            }
        }

        // Supprime
        $result = $this->trajetModel->delete($trajet['id']);
        $this->assertTrue($result);

        // Verifie
        $deleted = $this->trajetModel->find($trajet['id']);
        $this->assertFalse($deleted);

        $this->testTrajetId = null;
    }

    /**
     * Test comptage des trajets
     */
    public function testCount()
    {
        $count = $this->trajetModel->count();
        $this->assertIsInt((int)$count);
    }
}
