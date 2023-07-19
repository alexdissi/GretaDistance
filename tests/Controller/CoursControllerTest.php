<?php

namespace App\Test\Controller;

use App\Entity\Cours;
use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CoursControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CoursRepository $repository;
    private string $path = '/cours/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Cours::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Cour index');

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
            'cour[titre]' => 'Testing',
            'cour[contenu]' => 'Testing',
            'cour[dateDebut]' => 'Testing',
            'cour[dateFin]' => 'Testing',
            'cour[video]' => 'Testing',
            'cour[soustitre]' => 'Testing',
            'cour[fichier]' => 'Testing',
            'cour[image]' => 'Testing',
            'cour[alt]' => 'Testing',
            'cour[dateAjout]' => 'Testing',
            'cour[user]' => 'Testing',
            'cour[categorie]' => 'Testing',
        ]);

        self::assertResponseRedirects('/cours/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cours();
        $fixture->setTitre('My Title');
        $fixture->setContenu('My Title');
        $fixture->setDateDebut('My Title');
        $fixture->setDateFin('My Title');
        $fixture->setVideo('My Title');
        $fixture->setSoustitre('My Title');
        $fixture->setFichier('My Title');
        $fixture->setImage('My Title');
        $fixture->setAlt('My Title');
        $fixture->setDateAjout('My Title');
        $fixture->setUser('My Title');
        $fixture->setCategorie('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Cour');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cours();
        $fixture->setTitre('My Title');
        $fixture->setContenu('My Title');
        $fixture->setDateDebut('My Title');
        $fixture->setDateFin('My Title');
        $fixture->setVideo('My Title');
        $fixture->setSoustitre('My Title');
        $fixture->setFichier('My Title');
        $fixture->setImage('My Title');
        $fixture->setAlt('My Title');
        $fixture->setDateAjout('My Title');
        $fixture->setUser('My Title');
        $fixture->setCategorie('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'cour[titre]' => 'Something New',
            'cour[contenu]' => 'Something New',
            'cour[dateDebut]' => 'Something New',
            'cour[dateFin]' => 'Something New',
            'cour[video]' => 'Something New',
            'cour[soustitre]' => 'Something New',
            'cour[fichier]' => 'Something New',
            'cour[image]' => 'Something New',
            'cour[alt]' => 'Something New',
            'cour[dateAjout]' => 'Something New',
            'cour[user]' => 'Something New',
            'cour[categorie]' => 'Something New',
        ]);

        self::assertResponseRedirects('/cours/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitre());
        self::assertSame('Something New', $fixture[0]->getContenu());
        self::assertSame('Something New', $fixture[0]->getDateDebut());
        self::assertSame('Something New', $fixture[0]->getDateFin());
        self::assertSame('Something New', $fixture[0]->getVideo());
        self::assertSame('Something New', $fixture[0]->getSoustitre());
        self::assertSame('Something New', $fixture[0]->getFichier());
        self::assertSame('Something New', $fixture[0]->getImage());
        self::assertSame('Something New', $fixture[0]->getAlt());
        self::assertSame('Something New', $fixture[0]->getDateAjout());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getCategorie());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Cours();
        $fixture->setTitre('My Title');
        $fixture->setContenu('My Title');
        $fixture->setDateDebut('My Title');
        $fixture->setDateFin('My Title');
        $fixture->setVideo('My Title');
        $fixture->setSoustitre('My Title');
        $fixture->setFichier('My Title');
        $fixture->setImage('My Title');
        $fixture->setAlt('My Title');
        $fixture->setDateAjout('My Title');
        $fixture->setUser('My Title');
        $fixture->setCategorie('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/cours/');
    }
}
