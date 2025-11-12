<?php

namespace App\DataFixtures;

use App\Entity\Clima;
use App\Entity\DetalleSiniestro;
use App\Entity\Localidad;
use App\Entity\Persona;
use App\Entity\Rol;
use App\Entity\Sexo;
use App\Entity\Siniestro;
use App\Entity\TipoVehiculo;
use App\Entity\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. SEXO
        $sexos = [];
        $sexoData = ['Masculino', 'Femenino'];
        
        foreach ($sexoData as $desc) {
            $sexo = new Sexo();
            $sexo->setDescripcion($desc);
            $manager->persist($sexo);
            $sexos[] = $sexo;
        }

        // 2. ROL
        $roles = [];
        $rolData = ['Victima', 'Autor', 'Testigo'];
        
        foreach ($rolData as $desc) {
            $rol = new Rol();
            $rol->setDescripcion($desc);
            $manager->persist($rol);
            $roles[] = $rol;
        }

        // 3. CLIMA
        $climas = [];
        $climaData = ['Despejado', 'Nublado', 'Lluvioso', 'Tormentoso', 'Neblina', 'Llovizna', 'Ventoso'];
        
        foreach ($climaData as $desc) {
            $clima = new Clima();
            $clima->setDescripcion($desc);
            $manager->persist($clima);
            $climas[] = $clima;
        }

        // 4. TIPO VEHICULO
        $tiposVehiculo = [];
        $vehiculoData = ['Automóvil', 'Motocicleta', 'Camión', 'Camioneta', 'Colectivo', 'Bicicleta', 'Ciclomotor'];
        
        foreach ($vehiculoData as $desc) {
            $tipoVehiculo = new TipoVehiculo();
            $tipoVehiculo->setDescripcion($desc);
            $manager->persist($tipoVehiculo);
            $tiposVehiculo[] = $tipoVehiculo;
        }

        // 5. LOCALIDAD
        $localidades = [];
        $localidadData = [
            'Resistencia', 
            'Fontana', 
            'Barranqueras', 
            'Puerto Vilelas',
            'Margarita Belén',
            'Makallé',
            'Colonia Benítez',
            'Basail'
        ];
        
        foreach ($localidadData as $nombre) {
            $localidad = new Localidad();
            $localidad->setNombre($nombre);
            $manager->persist($localidad);
            $localidades[] = $localidad;
        }

        // 6. USUARIOS
        $admin = new Usuario();
        $admin->setEmail('admin@siniestros.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        $operador = new Usuario();
        $operador->setEmail('operador@siniestros.com');
        $operador->setRoles(['ROLE_OPERADOR']);
        $operador->setPassword($this->passwordHasher->hashPassword($operador, 'operador123'));
        $manager->persist($operador);

        // 7. PERSONAS
        $personas = [];
        $personasData = [
            ['Carlos', 'González', '32456789', '1985-03-15', 'CASADO', 0],
            ['María', 'Fernández', '28345678', '1990-07-22', 'SOLTERA', 1],
            ['Juan', 'Rodríguez', '35789456', '1982-11-30', 'CASADO', 0],
            ['Laura', 'Martínez', '40123456', '1995-05-18', 'SOLTERA', 1],
            ['Pedro', 'López', '29876543', '1988-09-25', 'DIVORCIADO', 0],
            ['Sofía', 'García', '38654321', '1992-12-08', 'CASADA', 1],
            ['Miguel', 'Benítez', '33567890', '1987-04-14', 'SOLTERO', 0]
        ];

        foreach ($personasData as $data) {
            $persona = new Persona();
            $persona->setNombre($data[0]);
            $persona->setApellido($data[1]);
            $persona->setDni($data[2]);
            $persona->setFechaNacimiento(new \DateTime($data[3]));
            $persona->setEstadoCivil($data[4]);
            $persona->setGenero($sexos[$data[5]]);
            $manager->persist($persona);
            $personas[] = $persona;
        }

        // 8. SINIESTROS
        $siniestros = [];
        $siniestrosData = [
            ['2025-01-15', 'Av. Alberdi', '2340', 'Colisión entre auto y moto en cruce', 0, 1],
            ['2025-03-08', 'Av. Las Heras', '1520', 'Choque múltiple por lluvia intensa', 1, 2],
            ['2025-04-22', 'Ruta 11', 'Km 15', 'Vuelco de camioneta', 2, 0],
            ['2025-06-10', 'Av. 9 de Julio', '3450', 'Atropello a peatón', 0, 3],
            ['2025-08-05', 'Calle French', '890', 'Colisión trasera entre dos autos', 3, 0],
            ['2025-09-18', 'Av. Sarmiento', '2100', 'Caída de motociclista', 1, 4],
            ['2025-10-30', 'Ruta 16', 'Km 8', 'Choque frontal entre camión y auto', 4, 2]
        ];

        foreach ($siniestrosData as $index => $data) {
            $siniestro = new Siniestro();
            $siniestro->setFecha(new \DateTime($data[0]));
            $siniestro->setCalle($data[1]);
            $siniestro->setAltura($data[2]);
            $siniestro->setUbicacion($data[1] . ' ' . $data[2]);
            $siniestro->setDescripcion($data[3]);
            $siniestro->setLocalidad($localidades[$data[4]]);
            $siniestro->setClima($climas[$data[5]]);
            $manager->persist($siniestro);
            $siniestros[] = $siniestro;
        }

        // 9. DETALLE SINIESTRO
        $estadosAlcoholicos = ['positivo', 'negativo', 'no_testeado'];
        $detallesData = [
            // Siniestro 1: Auto vs Moto
            [0, 0, 'negativo', '0.00', 'Conducía respetando normas', 0, 0], // Víctima en auto
            [0, 1, 'positivo', '1.15', 'Conducía en estado de ebriedad', 1, 1], // Autor en moto
            
            // Siniestro 2: Choque múltiple
            [1, 2, 'negativo', '0.00', 'Frenó por las condiciones climáticas', 0, 0], // Víctima
            [1, 3, 'negativo', '0.00', 'No pudo frenar a tiempo', 1, 3], // Autor en camioneta
            [1, 4, 'no_testeado', '0.00', 'Presenció el accidente', 2, null], // Testigo sin vehículo
            
            // Siniestro 3: Vuelco
            [2, 5, 'positivo', '0.85', 'Exceso de velocidad y alcohol', 1, 3], // Autor
            
            // Siniestro 4: Atropello
            [3, 6, 'negativo', '0.00', 'Cruzaba por senda peatonal', 0, null], // Víctima peatón
            [3, 0, 'negativo', '0.00', 'No vio al peatón', 1, 0], // Autor en auto
            
            // Siniestro 5: Colisión trasera
            [4, 1, 'negativo', '0.00', 'Estaba detenido', 0, 0], // Víctima
            [4, 2, 'negativo', '0.00', 'Distraído con celular', 1, 0], // Autor
            
            // Siniestro 6: Caída moto
            [5, 3, 'negativo', '0.00', 'Perdió control por bache', 0, 1], // Víctima motociclista
            
            // Siniestro 7: Choque frontal
            [6, 4, 'positivo', '1.42', 'Invadió carril contrario', 1, 2], // Autor camión
            [6, 5, 'negativo', '0.00', 'Circulaba por su mano', 0, 0], // Víctima auto
            [6, 6, 'no_testeado', '0.00', 'Circulaba detrás', 2, 0] // Testigo
        ];

        foreach ($detallesData as $data) {
            $detalle = new DetalleSiniestro();
            $detalle->setIdSiniestro($siniestros[$data[0]]);
            $detalle->setIdPersona($personas[$data[1]]);
            $detalle->setEstadoAlcoholico($data[2]);
            $detalle->setPorcentajeAlcohol($data[3]);
            $detalle->setObservaciones($data[4]);
            $detalle->setRol($roles[$data[5]]);
            
            // Tipo vehículo puede ser null (peatones/testigos)
            if ($data[6] !== null) {
                $detalle->setTipoVehiculo($tiposVehiculo[$data[6]]);
            }
            
            $manager->persist($detalle);
        }

        $manager->flush();
    }
}