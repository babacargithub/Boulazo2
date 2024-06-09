<?php

namespace App\Repository;
use App\Entity\Caisse;
use App\Utils\DateConstants as Cons;
use App\Entity\AbstractCaisse;

/**
 * CaisseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CaisseRepository extends \Doctrine\ORM\EntityRepository
{
    public function getSummary(Caisse $caisse, $condition, $date_debut=null,$date_fin=null )
    {
        $qb= $this->createQueryBuilder('c');
        
    }
    public function getTotauxJourneeSummary(Caisse $caisse, $condition, $date_debut=null,$date_fin=null )
    {
        $qb= $this->createQueryBuilder('c')
        ->join('App\Entity\HistoCaisse', 'hc')
                //->addSelect('hc')
         ->select('SUM(hc.montant) as total, DATE(hc.dateOp) as dateOp')
         //->addSelect('hc.dateOp')
         ->groupBy('dateOp')
                ->where('hc.deleted=:deletedOp')
                ->setParameter("deletedOp", false)
                ->andWhere('hc.caisse=:caisseOp')
                ->setParameter("caisseOp", $caisse)
                ->andWhere('hc.dateOp>=:dateDebut')
                ->setParameter("dateDebut", $date_debut)
               /* ->andWhere('hc.dateOp<=:dateFin')
                ->setParameter("dateFin", $date_debut)*/
                
                ;
                 
                return $qb->getQuery()->getResult();
    }
    public function getReleve(Caisse $caisse, $condition, $date_debut=null,$date_fin=null )
    {
        $qb= $this->createQueryBuilder('c')
                ->join('App\Entity\HistoCaisse', 'hc')
                //->select('c')
                ->select('hc')
                ->where('hc.deleted=:deletedOp')
                ->setParameter("deletedOp", false)
                ->andWhere('hc.caisse=:caisseOp')
                ->setParameter("caisseOp", $caisse);
                if(!is_null($date_debut))
                {
                    $qb->andWhere('DATE(hc.dateOp)>=:dateDebut')
                ->setParameter("dateDebut", $date_debut);
                    if(!is_null($date_fin))
                    {
                        $qb->andWhere('DATE(hc.dateOp)<=:dateFin')
                    ->setParameter("dateFin", $date_fin);
                    }
                }
                
                
        return $qb->getQuery()->getResult();
                
        
    }
    public function getSolde(Caisse $caisse,$typeSolde, $date)
    {
        $qb= $this->createQueryBuilder('c')
                ->join('App\Entity\HistoCaisse', 'hc')
                //->select('c')
                ->select('hc')
                ->where('hc.deleted=:deletedOp')
                ->setParameter("deletedOp", false)
                ->andWhere('hc.caisse=:caisseOp')
                ->setParameter("caisseOp", $caisse);
                
                        switch($typeSolde)
                        {
                        case AbstractCaisse::SODLE_DEBUT_JOUENEE:
                            //Pour obtenir le jour du solde précedent on doit soustraire un jour de la date renseignée
                            $new=explode('-',$date);
                            $new_date="".$new[0]."-".$new[1]."-".($new[2]-1)."";
                            $qb->andWhere('DATE(hc.dateOp)<:queryDate');
                            $qb->setParameter("queryDate", $date);
                            $qb->orderBy('hc.dateOp', 'DESC');
                            break;
                        case AbstractCaisse::SODLE_FIN_JOUENEE:
                            $qb->andWhere('DATE(hc.dateOp)=:queryDate');
                            $qb->setParameter("queryDate", $date);
                            $qb->orderBy('hc.dateOp', 'DESC');
                        }
                        
                        $qb->setMaxResults(1);
                $result=$qb->getQuery()->getResult();
        return isset($result[0]) ? $result[0]->getNouveauSolde(): 0;
                
        
    }
}
