<?php

namespace App\Repository;

use App\Entity\QuantitiesOption;
use App\Entity\ColourGroup;
use App\Entity\Colour;
use App\Entity\Model;
use App\Entity\ModelColour;
use App\Entity\ModelLength;
use App\Entity\ModelSeason;
use App\Entity\ModelSeasonColour;
use App\Entity\ModelSeasonLabel;
use App\Entity\ModelSeasonColourKeySelection;
use App\Entity\Season;
use App\Entity\Wash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuantitiesOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuantitiesOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuantitiesOption[]    findAll()
 * @method QuantitiesOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantitiesOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuantitiesOption::class);
    }

    /**
     * Contruye la consulta parcial para obtener los valores de Quantities presentes en la peticion actual
     * @param string $filter_criteria   Criterio de busqueda: Sell In, Sell Out o Minimum
     * @param string $search_column     Columna por la cual se ejecuta la busqueda de resultados
     * @param boolean $fields           Indica si queremos devolver campos especificos o los objetos completos
     * @return object $qb               Instancia de QueryBuilder resultante
     */
    public function findFilteredElementsQB($filter_criteria, $search_column, $fields = true)
    {
        $qb = $this->createQueryBuilder('q');

        if ($fields) { // solo campos especificos
            $qb = $qb->select('q.id', 'q.name', 'q.value');
        }

        $qb = $qb->join(ModelSeason::class, 'ms', 'WITH', "$search_column >= q.min AND $search_column <= q.max")
            ->join(Model::class, 'm', 'WITH', 'ms.model = m.id')
            ->join(ModelSeasonColour::class, 'msc', 'WITH', 'ms.id = msc.modelSeason')
            ->join(ModelColour::class, 'mc', 'WITH', 'msc.modelColour = mc.id')
            ->join(Season::class, 's', 'WITH', 'ms.season = s.id')
            ->join(Colour::class, 'col', 'WITH', 'mc.colour = col.id')
            ->leftJoin(ModelSeasonLabel::class, 'msl', 'WITH', 'msl.modelSeason = ms.id')
            ->leftJoin(ModelSeasonColourKeySelection::class, 'mscks', 'WITH', 'mscks.modelSeasonColour = msc.id')
            ->leftJoin(Wash::class, 'ws', 'WITH', 'm.wash = ws.id')
            ->leftJoin(ModelLength::class, 'ml', 'WITH', 'ml.model = m.id')
            ->andWhere('q.type = :type')
            ->setParameter('type', $filter_criteria)
            ->orderBy('q.itemOrder', 'ASC')
            ->groupBy('q.id');

        return $qb;
    }

    /**
     * Encuentra los valores marcados para este criterio de filtrado y los devuelve en formato array
     * @param array $elements   Listado de instancias marcadas para este filtro
     * @return array $result    Instancia de QueryBuilder resultante
     */
    public function findCurrentValues($elements)
    {
        $result = $this->createQueryBuilder('q')
            ->select('q.id', 'q.name', 'q.value')
            ->where('q.id IN (:elements)')
            ->setParameter('elements', $elements)
            ->orderBy('q.itemOrder', 'ASC')
            ->groupBy('q.id')
            ->getQuery()
            ->execute();

        return $result;
    }
}
