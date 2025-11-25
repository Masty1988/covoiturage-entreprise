<?php
namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Agence;

/**
 * Tests pour le model Agence
 */
class AgenceTest extends TestCase
{
    private $agenceModel;
    private $testAgenceId;

    protected function setUp(): void
    {
        $this->agenceModel = new Agence();
    }

    protected function tearDown(): void
    {
        // Nettoie les donnees de test
        if ($this->testAgenceId) {
            $this->agenceModel->delete($this->testAgenceId);
        }
    }

    /**
     * Test creation d'une agence
     */
    public function testCreate()
    {
        $data = [
            'nom_ville' => 'VilleTest_' . time()
        ];

        $result = $this->agenceModel->create($data);
        $this->assertTrue($result);

        // Recupere l'agence creee
        $agences = $this->agenceModel->all();
        $found = null;
        foreach ($agences as $agence) {
            if ($agence['nom_ville'] === $data['nom_ville']) {
                $found = $agence;
                break;
            }
        }

        $this->assertNotNull($found);
        $this->testAgenceId = $found['id'];
    }

    /**
     * Test mise a jour d'une agence
     */
    public function testUpdate()
    {
        // Cree une agence de test
        $data = ['nom_ville' => 'UpdateTest_' . time()];
        $this->agenceModel->create($data);

        // Trouve l'agence
        $agences = $this->agenceModel->all();
        $agence = null;
        foreach ($agences as $a) {
            if ($a['nom_ville'] === $data['nom_ville']) {
                $agence = $a;
                break;
            }
        }
        $this->testAgenceId = $agence['id'];

        // Met a jour
        $newData = ['nom_ville' => 'NouvelleVille_' . time()];
        $result = $this->agenceModel->update($agence['id'], $newData);
        $this->assertTrue($result);

        // Verifie
        $updated = $this->agenceModel->find($agence['id']);
        $this->assertEquals($newData['nom_ville'], $updated['nom_ville']);
    }

    /**
     * Test suppression d'une agence
     */
    public function testDelete()
    {
        // Cree une agence de test
        $data = ['nom_ville' => 'DeleteTest_' . time()];
        $this->agenceModel->create($data);

        // Trouve l'agence
        $agences = $this->agenceModel->all();
        $agence = null;
        foreach ($agences as $a) {
            if ($a['nom_ville'] === $data['nom_ville']) {
                $agence = $a;
                break;
            }
        }

        // Supprime
        $result = $this->agenceModel->delete($agence['id']);
        $this->assertTrue($result);

        // Verifie
        $deleted = $this->agenceModel->find($agence['id']);
        $this->assertFalse($deleted);

        $this->testAgenceId = null;
    }

    /**
     * Test recuperation de toutes les agences
     */
    public function testAll()
    {
        $agences = $this->agenceModel->all();
        $this->assertIsArray($agences);
        $this->assertGreaterThan(0, count($agences));
    }
}
