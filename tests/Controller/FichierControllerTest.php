<?php

namespace App\Test\Controller;

use App\Entity\Fichier;
use App\Repository\FichierRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FichierControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private FichierRepository $repository;
    private string $path = '/fichier/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Fichier::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Fichier index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'fichier[fichier]' => 'Testing',
            'fichier[user]' => 'Testing',
        ]);

        self::assertResponseRedirects('/fichier/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Fichier();
        $fixture->setFichier('My Title');
        $fixture->setUser('My Title');

        $this->repository->save($fixture);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Fichier');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Fichier();
        $fixture->setFichier('My Title');
        $fixture->setUser('My Title');

        $this->repository->save($fixture);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'fichier[fichier]' => 'Something New',
            'fichier[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/fichier/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getFichier());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Fichier();
        $fixture->setFichier('My Title');
        $fixture->setUser('My Title');

        $this->repository->save($fixture);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/fichier/');
    }
}
