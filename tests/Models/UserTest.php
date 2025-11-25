<?php
namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\User;

/**
 * Tests pour le model User
 */
class UserTest extends TestCase
{
    private $userModel;
    private $testUserId;

    protected function setUp(): void
    {
        $this->userModel = new User();
    }

    protected function tearDown(): void
    {
        // Nettoie les donnees de test
        if ($this->testUserId) {
            $this->userModel->delete($this->testUserId);
        }
    }

    /**
     * Test creation d'un utilisateur
     */
    public function testCreate()
    {
        $data = [
            'nom' => 'TestNom',
            'prenom' => 'TestPrenom',
            'email' => 'test_' . time() . '@test.fr',
            'mot_de_passe' => 'password123',
            'telephone' => '0600000000',
            'role' => 'employe'
        ];

        $result = $this->userModel->create($data);
        $this->assertTrue($result);

        // Recupere l'utilisateur cree
        $user = $this->userModel->findByEmail($data['email']);
        $this->assertNotFalse($user);
        $this->assertEquals($data['nom'], $user['nom']);
        $this->assertEquals($data['prenom'], $user['prenom']);

        // Stocke l'ID pour le nettoyage
        $this->testUserId = $user['id'];
    }

    /**
     * Test mise a jour d'un utilisateur
     */
    public function testUpdate()
    {
        // Cree un utilisateur de test
        $data = [
            'nom' => 'UpdateTest',
            'prenom' => 'Prenom',
            'email' => 'update_' . time() . '@test.fr',
            'mot_de_passe' => 'password123',
            'telephone' => '0600000000',
            'role' => 'employe'
        ];

        $this->userModel->create($data);
        $user = $this->userModel->findByEmail($data['email']);
        $this->testUserId = $user['id'];

        // Met a jour
        $newData = [
            'nom' => 'NouveauNom',
            'prenom' => 'NouveauPrenom',
            'email' => $data['email'],
            'telephone' => '0611111111'
        ];

        $result = $this->userModel->update($user['id'], $newData);
        $this->assertTrue($result);

        // Verifie la mise a jour
        $updated = $this->userModel->find($user['id']);
        $this->assertEquals('NouveauNom', $updated['nom']);
        $this->assertEquals('NouveauPrenom', $updated['prenom']);
    }

    /**
     * Test suppression d'un utilisateur
     */
    public function testDelete()
    {
        // Cree un utilisateur de test
        $data = [
            'nom' => 'DeleteTest',
            'prenom' => 'Prenom',
            'email' => 'delete_' . time() . '@test.fr',
            'mot_de_passe' => 'password123',
            'telephone' => '0600000000',
            'role' => 'employe'
        ];

        $this->userModel->create($data);
        $user = $this->userModel->findByEmail($data['email']);

        // Supprime
        $result = $this->userModel->delete($user['id']);
        $this->assertTrue($result);

        // Verifie la suppression
        $deleted = $this->userModel->find($user['id']);
        $this->assertFalse($deleted);

        // Pas besoin de nettoyer
        $this->testUserId = null;
    }

    /**
     * Test recherche par email
     */
    public function testFindByEmail()
    {
        $user = $this->userModel->findByEmail('admin@entreprise.fr');
        $this->assertNotFalse($user);
        $this->assertEquals('admin', $user['role']);
    }
}
