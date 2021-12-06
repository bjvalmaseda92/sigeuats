<?php

namespace SgvsBundle\Controller;


use SgvsBundle\Util\Util;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReportesController extends Controller
{
    public function tiposCasoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tiposcasos = $em->getRepository('SgvsBundle:TipoCaso')->findAll();


        return $this->render('SgvsBundle:Reportes:tipos-casos-get.html.twig', array(
            'tiposcasos' => $tiposcasos,
        ));
    }


    public function tiposSelectedAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tiposcasos = $em->getRepository('SgvsBundle:TipoCaso')->findAll();
        $tipo = $em->getRepository('SgvsBundle:TipoCaso')->find($request->get('tipos_casos'));

        $periodo = $request->get('tiempo');
        $tiempo=null;



        if ($periodo == "diario") {

            $fecha = new \DateTime($request->get('dia'));
            $tiempo=$request->get('dia');
            if ($request->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha = :fecha AND c.lugaringreso IS NOT null AND c.tipoCaso= :tipo';
                $query = $em->createQuery($dql);
                $query->setParameter('fecha', $fecha);
                $query->setParameter('tipo', $tipo);
                $casos = $query->getResult();
            } else {
                $casos = $em->getRepository('SgvsBundle:Caso')->findBy(array('fecha' => $fecha, 'enfermedad'=>$tipo));
            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha BETWEEN :fecha1 AND :fecha AND c.lugaringreso IS NOT null AND c.tipoCaso=:tipo';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha BETWEEN :fecha1 AND :fecha AND c.tipoCaso=:tipo';

            $fecha1=new \DateTime($request->get('dia'));
            $query = $em->createQuery($dqlingresados);
            $query2=$em->createQuery($dqldetectados);
            $query->setParameter('fecha', $fecha);
            $query->setParameter('tipo', $tipo);
            $query->setParameter('fecha1', $fecha1->sub(new \DateInterval('P30D')));
            $query2->setParameter('fecha', $fecha);
            $query2->setParameter('tipo', $tipo);
            $query2->setParameter('fecha1', $fecha1);
            $ingresados = $query->getResult();
            $detectados=$query2->getResult();




            $cingresados=array();
            $cdetectados=array();
            $ejeX=array();
            for($i=0;$i<30;$i++){
                $cingresados[$fecha->format('d-m-Y')]=0;
                $cdetectados[$fecha->format('d-m-Y')]=0;
                $ejeX[$i]=$fecha->format('d-m-Y');
                $fecha=$fecha->sub(new \DateInterval('P1D'));
            }

            foreach($detectados as $caso){
                $cdetectados[$caso->getFecha()->format('d-m-Y')]++;
            }
            foreach($ingresados as $caso){
                $cingresados[$caso->getFecha()->format('d-m-Y')]++;
            }
            $cdetectados=array_reverse($cdetectados);
            $cingresados=array_reverse($cingresados);
            $ejeX=array_reverse($ejeX);
            return $this->render('SgvsBundle:Reportes:tipos-casos-post.html.twig', array(
                'casos' => $casos,
                'fecha' =>$fecha->format('d-m-Y'),
                'periodo'=>$periodo,
                'ejex'=>$ejeX,
                'chartTitle'=>'Casos de '.$tipo.' en los últimos 30 días',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
                'tipo'=>$tipo,
                'tipos'=>$tiposcasos,
                'tipo_id'=>$tipo->getId(),
                'tiempo'=>$tiempo,
                'ingreso'=>$request->get('ingresados')
            ));

        } elseif ($periodo == "mensual") {
            $mes = $request->get('mes');


            $tiempo=$request->get('mes');


            if ($request->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE MONTH (c.fecha) = :mes AND c.tipoCaso=:tipo AND c.lugaringreso  IS NOT null ORDER BY c.fecha';
                $query = $em->createQuery($dql);
                $query->setParameter('mes', $mes);
                $query->setParameter('tipo', $tipo);
                $casos = $query->getResult();
            } else {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE MONTH (c.fecha) = :mes  AND c.tipoCaso=:tipo ORDER BY c.fecha';
                $query = $em->createQuery($dql);
                $query->setParameter('mes', $mes);
                $query->setParameter('tipo', $tipo);
                $casos = $query->getResult();
            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.tipoCaso=:tipo ORDER BY c.fecha';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.tipoCaso=:tipo ORDER BY c.fecha';
            $query = $em->createQuery($dqlingresados);
            $query2=$em->createQuery($dqldetectados);
            $query->setParameter('tipo', $tipo);
            $query2->setParameter('tipo', $tipo);

            $ingresados = $query->getResult();
            $detectados=$query2->getResult();


            $cingresados=array(0,0,0,0,0,0,0,0,0,0,0,0);
            $cdetectados=array(0,0,0,0,0,0,0,0,0,0,0,0);

            $meses=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

            foreach($ingresados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cingresados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cingresados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cingresados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cingresados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cingresados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cingresados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cingresados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cingresados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cingresados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cingresados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cingresados[10]++;
                }else{
                    $cingresados[11]++;
                }
            }

            foreach($detectados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cdetectados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cdetectados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cdetectados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cdetectados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cdetectados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cdetectados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cdetectados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cdetectados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cdetectados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cdetectados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cdetectados[10]++;
                }else{
                    $cdetectados[11]++;
                }
            }

            return $this->render('SgvsBundle:Reportes:tipos-casos-post.html.twig', array(
                'casos' => $casos,
                'fecha' =>$mes,
                'periodo'=>$periodo,
                'ejex'=>$meses,
                'chartTitle'=>'Casos de '.$tipo.' en los últimos 12 meses',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
                'tipo'=>$tipo,
                'tipos'=>$tiposcasos,
                'tipo_id'=>$tipo->getId(),
                'tiempo'=>$tiempo,
                'ingreso'=>$request->get('ingresados')
            ));

        } elseif($periodo=='semanal') {
            $semana = $request->get('semana');
            $tiempo=$request->get('semana');

            if ($request->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE WEEK (c.fecha) = :semana AND c.lugaringreso IS NOT null ORDER BY c.fecha AND c.tipoCaso=:tipo';
                $query = $em->createQuery($dql);
                $query->setParameter('semana', $semana);
                $query->setParameter('tipo', $tipo);
                $casos = $query->getResult();
            } else {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE WEEK (c.fecha) = :semana AND c.tipoCaso=:tipo ORDER BY c.fecha';
                $query = $em->createQuery($dql);
                $query->setParameter('semana', $semana);
                $query->setParameter('tipo', $tipo);
                $casos = $query->getResult();
            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.tipoCaso=:tipo';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.tipoCaso=:tipo';
            $query = $em->createQuery($dqlingresados);
            $query2 = $em->createQuery($dqldetectados);
            $query->setParameter('tipo', $tipo);
            $query2->setParameter('tipo', $tipo);
            $ingresados = $query->getResult();
            $detectados=$query2->getResult();

            $cingresados=array();
            $cdetectados=array();
            $ejeX=array();
            for($i=1;$i<54;$i++){
                $cingresados[$i]=0;
                $cdetectados[$i]=0;
                $ejeX[$i]=$i.'º';
            }

            foreach($ingresados as $caso){
                $cingresados[$caso->getFecha()->format('W')]++;
            }
            foreach($detectados as $caso){
                $cdetectados[$caso->getFecha()->format('W')]++;
            }

            return $this->render('SgvsBundle:Reportes:tipos-casos-post.html.twig', array(
                'casos' => $casos,
                'fecha' =>$tiempo,
                'periodo'=>$periodo,
                'ejex'=>$ejeX,
                'chartTitle'=>'Casos de '.$tipo.' por semanas estadísticas',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
                'tipo'=>$tipo,
                'tipos'=>$tiposcasos,
                'tipo_id'=>$tipo->getId(),
                'tiempo'=>$tiempo,
                'ingreso'=>$request->get('ingresados')
            ));



        }else {

            $mes = date('m');
            $tiempo='nada';

            if ($request->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.tipoCaso= :tipo';
                $query = $em->createQuery($dql);
                $query->setParameter('tipo', $tipo);

                $casos = $query->getResult();
            } else {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE  c.tipoCaso= :tipo';
                $query = $em->createQuery($dql);
                $query->setParameter('tipo', $tipo);
                $casos = $query->getResult();

            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.tipoCaso=:tipo ORDER BY c.fecha';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.tipoCaso=:tipo ORDER BY c.fecha';
            $query = $em->createQuery($dqlingresados);
            $query2=$em->createQuery($dqldetectados);
            $query->setParameter('tipo', $tipo);
            $query2->setParameter('tipo', $tipo);

            $ingresados = $query->getResult();
            $detectados=$query2->getResult();


            $cingresados=array(0,0,0,0,0,0,0,0,0,0,0,0);
            $cdetectados=array(0,0,0,0,0,0,0,0,0,0,0,0);

            $meses=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

            foreach($ingresados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cingresados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cingresados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cingresados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cingresados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cingresados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cingresados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cingresados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cingresados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cingresados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cingresados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cingresados[10]++;
                }else{
                    $cingresados[11]++;
                }
            }

            foreach($detectados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cdetectados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cdetectados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cdetectados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cdetectados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cdetectados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cdetectados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cdetectados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cdetectados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cdetectados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cdetectados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cdetectados[10]++;
                }else{
                    $cdetectados[11]++;
                }
            }

            return $this->render('SgvsBundle:Reportes:tipos-casos-post.html.twig', array(
                'casos' => $casos,
                'fecha' =>$mes,
                'periodo'=>$periodo,
                'ejex'=>$meses,
                'chartTitle'=>'Casos de '.$tipo.' en los últimos 12 meses',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
                'tipo'=>$tipo,
                'tipos'=>$tiposcasos,
                'tipo_id'=>$tipo->getId(),
                'tiempo'=>$tiempo,
                'ingreso'=>$request->get('ingresados')
            ));
        }
    }

    public function enfermedadesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $enfermedades = $em->getRepository('SgvsBundle:Enfermedad')->findAll();


        return $this->render('SgvsBundle:Reportes:enfermedades-get.html.twig', array(
            'enfermedades' => $enfermedades,
        ));
    }


    public function enfermedadesSelectedAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $enfermedades = $em->getRepository('SgvsBundle:Enfermedad')->findAll();
        $enfermedad = $em->getRepository('SgvsBundle:Enfermedad')->find($request->get('enfermedad'));

        $periodo = $request->get('tiempo');
        $tiempo=null;

        if ($periodo == "diario") {

            $fecha = new \DateTime($request->get('dia'));
            $tiempo=$request->get('dia');
            if ($request->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha = :fecha AND c.lugaringreso IS NOT null AND c.enfermedad=:enfermedad';
                $query = $em->createQuery($dql);
                $query->setParameter('fecha', $fecha);
                $query->setParameter('enfermedad', $enfermedad);
                $casos = $query->getResult();
            } else {
                $casos = $em->getRepository('SgvsBundle:Caso')->findBy(array('fecha' => $fecha, 'enfermedad'=>$enfermedad));
            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha BETWEEN :fecha1 AND :fecha AND c.lugaringreso IS NOT null AND c.enfermedad =:enfermedad';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha BETWEEN :fecha1 AND :fecha AND c.enfermedad =:enfermedad';

            $fecha1=new \DateTime($request->get('dia'));
            $query = $em->createQuery($dqlingresados);
            $query2=$em->createQuery($dqldetectados);
            $query->setParameter('fecha', $fecha);
            $query->setParameter('enfermedad', $enfermedad);
            $query->setParameter('fecha1', $fecha1->sub(new \DateInterval('P30D')));
            $query2->setParameter('fecha', $fecha);
            $query2->setParameter('enfermedad', $enfermedad);
            $query2->setParameter('fecha1', $fecha1);
            $ingresados = $query->getResult();
            $detectados=$query2->getResult();




            $cingresados=array();
            $cdetectados=array();
            $ejeX=array();
            for($i=0;$i<30;$i++){
                $cingresados[$fecha->format('d-m-Y')]=0;
                $cdetectados[$fecha->format('d-m-Y')]=0;
                $ejeX[$i]=$fecha->format('d-m-Y');
                $fecha=$fecha->sub(new \DateInterval('P1D'));
            }

            foreach($detectados as $caso){
                $cdetectados[$caso->getFecha()->format('d-m-Y')]++;
            }
            foreach($ingresados as $caso){
                $cingresados[$caso->getFecha()->format('d-m-Y')]++;
            }
            $cdetectados=array_reverse($cdetectados);
            $cingresados=array_reverse($cingresados);
            $ejeX=array_reverse($ejeX);
            return $this->render('SgvsBundle:Reportes:enfermedades-post.html.twig', array(
                'casos' => $casos,
                'fecha' =>$fecha->format('d-m-Y'),
                'periodo'=>$periodo,
                'ejex'=>$ejeX,
                'chartTitle'=>'Casos de '.$enfermedad.' en los últimos 30 días',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
                'enfermedad'=>$enfermedad,
                'enfermedades'=>$enfermedades,
                'enfermedad_id'=>$enfermedad->getId(),
                'tiempo'=>$tiempo,
                'ingreso'=>$request->get('ingresados')
            ));

        } elseif ($periodo == "mensual") {
            $mes = $request->get('mes');


            $tiempo=$request->get('mes');


            if ($request->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE MONTH (c.fecha) = :mes AND c.enfermedad=:enfermedad AND c.lugaringreso  IS NOT null ORDER BY c.fecha';
                $query = $em->createQuery($dql);
                $query->setParameter('mes', $mes);
                $query->setParameter('enfermedad', $enfermedad);
                $casos = $query->getResult();
            } else {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE MONTH (c.fecha) = :mes  AND c.enfermedad=:enfermedad ORDER BY c.fecha';
                $query = $em->createQuery($dql);
                $query->setParameter('mes', $mes);
                $query->setParameter('enfermedad', $enfermedad);
                $casos = $query->getResult();
            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.enfermedad=:enfermedad ORDER BY c.fecha';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.enfermedad=:enfermedad ORDER BY c.fecha';
            $query = $em->createQuery($dqlingresados);
            $query2=$em->createQuery($dqldetectados);
            $query->setParameter('enfermedad', $enfermedad);
            $query2->setParameter('enfermedad', $enfermedad);

            $ingresados = $query->getResult();
            $detectados=$query2->getResult();


            $cingresados=array(0,0,0,0,0,0,0,0,0,0,0,0);
            $cdetectados=array(0,0,0,0,0,0,0,0,0,0,0,0);

            $meses=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

            foreach($ingresados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cingresados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cingresados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cingresados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cingresados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cingresados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cingresados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cingresados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cingresados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cingresados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cingresados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cingresados[10]++;
                }else{
                    $cingresados[11]++;
                }
            }

            foreach($detectados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cdetectados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cdetectados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cdetectados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cdetectados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cdetectados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cdetectados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cdetectados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cdetectados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cdetectados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cdetectados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cdetectados[10]++;
                }else{
                    $cdetectados[11]++;
                }
            }

            return $this->render('SgvsBundle:Reportes:enfermedades-post.html.twig', array(
                'casos' => $casos,
                'fecha' =>$mes,
                'periodo'=>$periodo,
                'ejex'=>$meses,
                'chartTitle'=>'Casos de '.$enfermedad.' en los últimos 12 meses',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
                'enfermedad'=>$enfermedad,
                'enfermedades'=>$enfermedades,
                'enfermedad_id'=>$enfermedad->getId(),
                'tiempo'=>$tiempo,
                'ingreso'=>$request->get('ingresados')
            ));

        } elseif($periodo=='semanal') {
            $semana = $request->get('semana');
            $tiempo=$request->get('semana');

            if ($request->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE WEEK (c.fecha) = :semana AND c.lugaringreso IS NOT null AND c.enfermedad=:enfermedad ORDER BY c.fecha';
                $query = $em->createQuery($dql);
                $query->setParameter('semana', $semana);
                $query->setParameter('enfermedad', $enfermedad);
                $casos = $query->getResult();
            } else {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE WEEK (c.fecha) = :semana AND c.enfermedad=:enfermedad ORDER BY c.fecha';
                $query = $em->createQuery($dql);
                $query->setParameter('semana', $semana);
                $query->setParameter('enfermedad', $enfermedad);
                $casos = $query->getResult();
            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.enfermedad=:enfermedad';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.enfermedad=:enfermedad';
            $query = $em->createQuery($dqlingresados);
            $query2 = $em->createQuery($dqldetectados);
            $query->setParameter('enfermedad', $enfermedad);
            $query2->setParameter('enfermedad', $enfermedad);
            $ingresados = $query->getResult();
            $detectados=$query2->getResult();

            $cingresados=array();
            $cdetectados=array();
            $ejeX=array();
            for($i=1;$i<54;$i++){
                $cingresados[$i]=0;
                $cdetectados[$i]=0;
                $ejeX[$i]=$i.'º';
            }

            foreach($ingresados as $caso){
                $cingresados[$caso->getFecha()->format('W')]++;
            }
            foreach($detectados as $caso){
                $cdetectados[$caso->getFecha()->format('W')]++;
            }

            return $this->render('SgvsBundle:Reportes:enfermedades-post.html.twig', array(
                'casos' => $casos,
                'fecha' =>$tiempo,
                'periodo'=>$periodo,
                'ejex'=>$ejeX,
                'chartTitle'=>'Casos de '.$enfermedad.' por semanas estadísticas',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
                'enfermedad'=>$enfermedad,
                'enfermedades'=>$enfermedades,
                'enfermedad_id'=>$enfermedad->getId(),
                'tiempo'=>$tiempo,
                'ingreso'=>$request->get('ingresados')
            ));



        }else {

            $mes = date('m');
            $tiempo='nada';

            if ($request->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.enfermedad=:enfermedad';
                $query = $em->createQuery($dql);
                $query->setParameter('enfermedad', $enfermedad);

                $casos = $query->getResult();
            } else {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE  c.enfermedad=:enfermedad';
                $query = $em->createQuery($dql);
                $query->setParameter('enfermedad', $enfermedad);
                $casos = $query->getResult();

            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.enfermedad=:enfermedad ORDER BY c.fecha';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.enfermedad=:enfermedad ORDER BY c.fecha';
            $query = $em->createQuery($dqlingresados);
            $query2=$em->createQuery($dqldetectados);
            $query->setParameter('enfermedad', $enfermedad);
            $query2->setParameter('enfermedad', $enfermedad);

            $ingresados = $query->getResult();
            $detectados=$query2->getResult();


            $cingresados=array(0,0,0,0,0,0,0,0,0,0,0,0);
            $cdetectados=array(0,0,0,0,0,0,0,0,0,0,0,0);

            $meses=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

            foreach($ingresados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cingresados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cingresados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cingresados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cingresados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cingresados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cingresados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cingresados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cingresados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cingresados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cingresados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cingresados[10]++;
                }else{
                    $cingresados[11]++;
                }
            }

            foreach($detectados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cdetectados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cdetectados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cdetectados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cdetectados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cdetectados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cdetectados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cdetectados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cdetectados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cdetectados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cdetectados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cdetectados[10]++;
                }else{
                    $cdetectados[11]++;
                }
            }

            return $this->render('SgvsBundle:Reportes:enfermedades-post.html.twig', array(
                'casos' => $casos,
                'fecha' =>$mes,
                'periodo'=>$periodo,
                'ejex'=>$meses,
                'chartTitle'=>'Casos de '.$enfermedad.' en los últimos 12 meses',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
                'enfermedad'=>$enfermedad,
                'enfermedades'=>$enfermedades,
                'enfermedad_id'=>$enfermedad->getId(),
                'tiempo'=>$tiempo,
                'ingreso'=>$request->get('ingresados')
            ));
        }


    }




    public function tiposPDFAction(Request $request)
    {
        $periodo = $request->query->get('periodo');
        $em = $this->getDoctrine()->getManager();
        $tipo = $em->getRepository('SgvsBundle:tipoCaso')->find($request->query->get('tipo'));
        if ($periodo == "diario") {


            $fecha = new \DateTime($request->query->get('tiempo'));
            if ($request->query->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha = :fecha AND c.lugaringreso IS NOT null AND c.tipoCaso= :tipo';
                $query = $em->createQuery($dql);
                $query->setParameter('fecha', $fecha);
                $query->setParameter('tipo', $tipo);
                $casos = $query->getResult();
            } else {
                $casos = $em->getRepository('SgvsBundle:Caso')->findBy(array('fecha' => $fecha, 'enfermedad'=>$tipo));
            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha BETWEEN :fecha1 AND :fecha AND c.lugaringreso IS NOT null AND c.tipoCaso= :tipo';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha BETWEEN :fecha1 AND :fecha AND c.tipoCaso= :tipo';

            $fecha1=new \DateTime($request->get('dia'));
            $query = $em->createQuery($dqlingresados);
            $query2=$em->createQuery($dqldetectados);
            $query->setParameter('fecha', $fecha);
            $query->setParameter('tipo', $tipo);
            $query->setParameter('fecha1', $fecha1->sub(new \DateInterval('P30D')));
            $query2->setParameter('fecha', $fecha);
            $query2->setParameter('tipo', $tipo);
            $query2->setParameter('fecha1', $fecha1);
            $ingresados = $query->getResult();
            $detectados=$query2->getResult();




            $cingresados=array();
            $cdetectados=array();
            $ejeX=array();
            for($i=0;$i<30;$i++){
                $cingresados[$fecha->format('d-m-Y')]=0;
                $cdetectados[$fecha->format('d-m-Y')]=0;
                $ejeX[$i]=$fecha->format('d-m-Y');
                $fecha=$fecha->sub(new \DateInterval('P1D'));
            }

            foreach($detectados as $caso){
                $cdetectados[$caso->getFecha()->format('d-m-Y')]++;
            }
            foreach($ingresados as $caso){
                $cingresados[$caso->getFecha()->format('d-m-Y')]++;
            }
            $cdetectados=array_reverse($cdetectados);
            $cingresados=array_reverse($cingresados);
            $ejeX=array_reverse($ejeX);



            $html= $this->renderView('SgvsBundle:Reportes:tipos-casos-pdf.html.twig', array(
                'casos' => $casos,
                'tipo' => $tipo,
                'fecha'=>$fecha->format('d-m-Y'),
                'ejex'=>$ejeX,
                'chartTitle'=>'Casos de '.$tipo.' en los últimos 30 días',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,

            ));

           return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="Informe de '.$tipo.' Detectados '.$fecha->format('d-m-Y').'.pdf"'
                )
            );

        }elseif ($periodo == "mensual") {
            $mes = $request->query->get('tiempo');

            if ($request->query->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE MONTH (c.fecha) = :mes AND c.tipoCaso= :tipo AND c.lugaringreso  IS NOT null ORDER BY c.fecha';
                $query = $em->createQuery($dql);
                $query->setParameter('mes', $mes);
                $query->setParameter('tipo', $tipo);
                $casos = $query->getResult();
            } else {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE MONTH (c.fecha) = :mes  AND c.tipoCaso= :tipo ORDER BY c.fecha';
                $query = $em->createQuery($dql);
                $query->setParameter('mes', $mes);
                $query->setParameter('tipo', $tipo);
                $casos = $query->getResult();
            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.tipoCaso= :tipo ORDER BY c.fecha';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.tipoCaso= :tipo ORDER BY c.fecha';
            $query = $em->createQuery($dqlingresados);
            $query2=$em->createQuery($dqldetectados);
            $query->setParameter('tipo', $tipo);
            $query2->setParameter('tipo', $tipo);

            $ingresados = $query->getResult();
            $detectados=$query2->getResult();


            $cingresados=array(0,0,0,0,0,0,0,0,0,0,0,0);
            $cdetectados=array(0,0,0,0,0,0,0,0,0,0,0,0);

            $meses=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

            foreach($ingresados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cingresados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cingresados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cingresados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cingresados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cingresados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cingresados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cingresados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cingresados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cingresados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cingresados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cingresados[10]++;
                }else{
                    $cingresados[11]++;
                }
            }

            foreach($detectados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cdetectados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cdetectados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cdetectados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cdetectados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cdetectados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cdetectados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cdetectados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cdetectados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cdetectados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cdetectados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cdetectados[10]++;
                }else{
                    $cdetectados[11]++;
                }
            }



            $fecha=Util::mesString($mes).' '.date('Y');

            $html = $this->renderView('SgvsBundle:Reportes:tipos-casos-pdf.html.twig', array(
                'casos' => $casos,
                'tipo' => $tipo,
                'fecha'=>$fecha,
                'ejex'=>$meses,
                'chartTitle'=>'Casos de casos de '.$tipo.' en los últimos 12 meses',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
            ));

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="Informe de '.$tipo.' Detectados '.$fecha.'.pdf"'
                )
            );
        }elseif($periodo=='semanal') {

            $semana = $request->query->get('semana');

            if ($request->query->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE WEEK (c.fecha) = :semana AND c.tipoCaso= :tipo IS NOT null ORDER BY c.fecha AND c.enfermedad=:enfermedad';
                $query = $em->createQuery($dql);
                $query->setParameter('semana', $semana);
                $query->setParameter('tipo', $tipo);
                $casos = $query->getResult();
            } else {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE WEEK (c.fecha) = :semana AND c.tipoCaso= :tipo ORDER BY c.fecha';
                $query = $em->createQuery($dql);
                $query->setParameter('semana', $semana);
                $query->setParameter('tipo', $tipo);
                $casos = $query->getResult();
            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.tipoCaso= :tipo';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.enfermedad=:enfermedad';
            $query = $em->createQuery($dqlingresados);
            $query2 = $em->createQuery($dqldetectados);
            $query->setParameter('tipo', $tipo);
            $query2->setParameter('tipo', $tipo);
            $ingresados = $query->getResult();
            $detectados = $query2->getResult();

            $cingresados = array();
            $cdetectados = array();
            $ejeX = array();
            for ($i = 1; $i < 54; $i++) {
                $cingresados[$i] = 0;
                $cdetectados[$i] = 0;
                $ejeX[$i] = $i . 'º';
            }

            foreach ($ingresados as $caso) {
                $cingresados[$caso->getFecha()->format('W')]++;
            }
            foreach ($detectados as $caso) {
                $cdetectados[$caso->getFecha()->format('W')]++;
            }

            $html = $this->renderView('SgvsBundle:Reportes:tipos-casos-pdf.html.twig', array(
                'casos' => $casos,
                'tipo' => $tipo,
                'fecha'=>'Semana '.$semana.'º',
                'ejex'=>$ejeX,
                'chartTitle'=>'Casos de '.$tipo.' por semanas estadísticas',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
            ));

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="Informe de '.$tipo.' Detectados Semana estadística '.$semana.'º.pdf"'
                )
            );
        }else {
            if ($request->get('ingreso') =='on') {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.tipoCaso= :tipo';
                $query = $em->createQuery($dql);
                $query->setParameter('tipo', $tipo);
                $casos = $query->getResult();
            } else {
                $casos = $em->getRepository('SgvsBundle:Caso')->findAll(array('enfermedad'=>$tipo));
            }
            $fecha=date('d-m-Y');

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.c.tipoCaso= :tipo ORDER BY c.fecha';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.tipoCaso= :tipo ORDER BY c.fecha';
            $query = $em->createQuery($dqlingresados);
            $query2=$em->createQuery($dqldetectados);
            $query->setParameter('tipo', $tipo);
            $query2->setParameter('tipo', $tipo);

            $ingresados = $query->getResult();
            $detectados=$query2->getResult();


            $cingresados=array(0,0,0,0,0,0,0,0,0,0,0,0);
            $cdetectados=array(0,0,0,0,0,0,0,0,0,0,0,0);

            $meses=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

            foreach($ingresados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cingresados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cingresados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cingresados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cingresados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cingresados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cingresados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cingresados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cingresados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cingresados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cingresados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cingresados[10]++;
                }else{
                    $cingresados[11]++;
                }
            }

            foreach($detectados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cdetectados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cdetectados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cdetectados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cdetectados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cdetectados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cdetectados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cdetectados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cdetectados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cdetectados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cdetectados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cdetectados[10]++;
                }else{
                    $cdetectados[11]++;
                }
            }

            $html = $this->renderView('SgvsBundle:Reportes:tipos-casos-pdf.html.twig', array(
                'casos' => $casos,
                'tipo' => $tipo,
                'fecha'=>$fecha,
                'ejex'=>$meses,
                'chartTitle'=>'Casos de '.$tipo.' en los últimos 12 meses',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
            ));

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="Informe de '.$tipo.' Detectados.pdf"'
                )
            );


        }

    }

    public function enfermedadPDFAction(Request $request)
    {


        $periodo = $request->query->get('periodo');
        $em = $this->getDoctrine()->getManager();
        $enfermedad = $em->getRepository('SgvsBundle:Enfermedad')->find($request->query->get('enfermedad'));
        if ($periodo == "diario") {


            $fecha = new \DateTime($request->query->get('tiempo'));
            if ($request->query->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha = :fecha AND c.lugaringreso IS NOT null AND c.enfermedad= :enfermedad';
                $query = $em->createQuery($dql);
                $query->setParameter('fecha', $fecha);
                $query->setParameter('enfermedad', $enfermedad);
                $casos = $query->getResult();
            } else {
                $casos = $em->getRepository('SgvsBundle:Caso')->findBy(array('fecha' => $fecha, 'enfermedad'=>$enfermedad));
            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha BETWEEN :fecha1 AND :fecha AND c.lugaringreso IS NOT null AND c.enfermedad =:enfermedad';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha BETWEEN :fecha1 AND :fecha AND c.enfermedad =:enfermedad';

            $fecha1=new \DateTime($request->get('dia'));
            $query = $em->createQuery($dqlingresados);
            $query2=$em->createQuery($dqldetectados);
            $query->setParameter('fecha', $fecha);
            $query->setParameter('enfermedad', $enfermedad);
            $query->setParameter('fecha1', $fecha1->sub(new \DateInterval('P30D')));
            $query2->setParameter('fecha', $fecha);
            $query2->setParameter('enfermedad', $enfermedad);
            $query2->setParameter('fecha1', $fecha1);
            $ingresados = $query->getResult();
            $detectados=$query2->getResult();




            $cingresados=array();
            $cdetectados=array();
            $ejeX=array();
            for($i=0;$i<30;$i++){
                $cingresados[$fecha->format('d-m-Y')]=0;
                $cdetectados[$fecha->format('d-m-Y')]=0;
                $ejeX[$i]=$fecha->format('d-m-Y');
                $fecha=$fecha->sub(new \DateInterval('P1D'));
            }

            foreach($detectados as $caso){
                $cdetectados[$caso->getFecha()->format('d-m-Y')]++;
            }
            foreach($ingresados as $caso){
                $cingresados[$caso->getFecha()->format('d-m-Y')]++;
            }
            $cdetectados=array_reverse($cdetectados);
            $cingresados=array_reverse($cingresados);
            $ejeX=array_reverse($ejeX);



            $html = $this->renderView('SgvsBundle:Reportes:enfermedades-pdf.html.twig', array(
                'casos' => $casos,
                'tipo' => $enfermedad,
                'fecha'=>$fecha->format('d-m-Y'),
                'ejex'=>$ejeX,
                'chartTitle'=>'Casos de '.$enfermedad.' en los últimos 30 días',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,

            ));

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="Informe de '.$enfermedad.' Detectados '.$fecha->format('d-m-Y').'.pdf"'
                )
            );

        }elseif ($periodo == "mensual") {
            $mes = $request->query->get('tiempo');

            if ($request->query->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE MONTH (c.fecha) = :mes AND c.enfermedad=:enfermedad AND c.lugaringreso  IS NOT null ORDER BY c.fecha';
                $query = $em->createQuery($dql);
                $query->setParameter('mes', $mes);
                $query->setParameter('enfermedad', $enfermedad);
                $casos = $query->getResult();
            } else {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE MONTH (c.fecha) = :mes  AND c.enfermedad=:enfermedad ORDER BY c.fecha';
                $query = $em->createQuery($dql);
                $query->setParameter('mes', $mes);
                $query->setParameter('enfermedad', $enfermedad);
                $casos = $query->getResult();
            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.enfermedad=:enfermedad ORDER BY c.fecha';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.enfermedad=:enfermedad ORDER BY c.fecha';
            $query = $em->createQuery($dqlingresados);
            $query2=$em->createQuery($dqldetectados);
            $query->setParameter('enfermedad', $enfermedad);
            $query2->setParameter('enfermedad', $enfermedad);

            $ingresados = $query->getResult();
            $detectados=$query2->getResult();


            $cingresados=array(0,0,0,0,0,0,0,0,0,0,0,0);
            $cdetectados=array(0,0,0,0,0,0,0,0,0,0,0,0);

            $meses=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

            foreach($ingresados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cingresados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cingresados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cingresados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cingresados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cingresados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cingresados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cingresados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cingresados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cingresados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cingresados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cingresados[10]++;
                }else{
                    $cingresados[11]++;
                }
            }

            foreach($detectados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cdetectados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cdetectados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cdetectados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cdetectados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cdetectados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cdetectados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cdetectados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cdetectados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cdetectados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cdetectados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cdetectados[10]++;
                }else{
                    $cdetectados[11]++;
                }
            }



            $fecha=Util::mesString($mes).' '.date('Y');

            $html = $this->renderView('SgvsBundle:Reportes:enfermedades-pdf.html.twig', array(
                'casos' => $casos,
                'tipo' => $enfermedad,
                'fecha'=>$fecha,
                'ejex'=>$meses,
                'chartTitle'=>'Casos de '.$enfermedad.' en los últimos 12 meses',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
            ));

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="Informe de '.$enfermedad.' Detectados '.$fecha.'.pdf"'
                )
            );
        }elseif($periodo=='semanal') {

            $semana = $request->query->get('semana');

            if ($request->query->get('ingresados') == true) {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE WEEK (c.fecha) = :semana AND c.lugaringreso IS NOT null ORDER BY c.fecha AND c.enfermedad=:enfermedad';
                $query = $em->createQuery($dql);
                $query->setParameter('semana', $semana);
                $query->setParameter('enfermedad', $enfermedad);
                $casos = $query->getResult();
            } else {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE WEEK (c.fecha) = :semana AND c.enfermedad=:enfermedad ORDER BY c.fecha';
                $query = $em->createQuery($dql);
                $query->setParameter('semana', $semana);
                $query->setParameter('enfermedad', $enfermedad);
                $casos = $query->getResult();
            }

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.enfermedad=:enfermedad';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.enfermedad=:enfermedad';
            $query = $em->createQuery($dqlingresados);
            $query2 = $em->createQuery($dqldetectados);
            $query->setParameter('enfermedad', $enfermedad);
            $query2->setParameter('enfermedad', $enfermedad);
            $ingresados = $query->getResult();
            $detectados = $query2->getResult();

            $cingresados = array();
            $cdetectados = array();
            $ejeX = array();
            for ($i = 1; $i < 54; $i++) {
                $cingresados[$i] = 0;
                $cdetectados[$i] = 0;
                $ejeX[$i] = $i . 'º';
            }

            foreach ($ingresados as $caso) {
                $cingresados[$caso->getFecha()->format('W')]++;
            }
            foreach ($detectados as $caso) {
                $cdetectados[$caso->getFecha()->format('W')]++;
            }

            $html = $this->renderView('SgvsBundle:Reportes:enfermedades-pdf.html.twig', array(
                'casos' => $casos,
                'tipo' => $enfermedad,
                'fecha'=>'Semana '.$semana.'º',
                'ejex'=>$ejeX,
                'chartTitle'=>'Casos de '.$enfermedad.' por semanas estadísticas',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
            ));

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="Informe de '.$enfermedad.' Detectados Semana estadística '.$semana.'º.pdf"'
                )
            );
        }else {
            if ($request->get('ingreso') =='on') {
                $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.enfermedad =:enfermedad';
                $query = $em->createQuery($dql);
                $query->setParameter('enfermedad', $enfermedad);
                $casos = $query->getResult();
            } else {
                $casos = $em->getRepository('SgvsBundle:Caso')->findAll(array('enfermedad'=>$enfermedad));
            }
            $fecha=date('d-m-Y');

            $dqlingresados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null AND c.enfermedad=:enfermedad ORDER BY c.fecha';
            $dqldetectados = 'SELECT c FROM SgvsBundle:Caso c WHERE c.enfermedad=:enfermedad ORDER BY c.fecha';
            $query = $em->createQuery($dqlingresados);
            $query2=$em->createQuery($dqldetectados);
            $query->setParameter('enfermedad', $enfermedad);
            $query2->setParameter('enfermedad', $enfermedad);

            $ingresados = $query->getResult();
            $detectados=$query2->getResult();


            $cingresados=array(0,0,0,0,0,0,0,0,0,0,0,0);
            $cdetectados=array(0,0,0,0,0,0,0,0,0,0,0,0);

            $meses=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

            foreach($ingresados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cingresados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cingresados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cingresados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cingresados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cingresados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cingresados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cingresados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cingresados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cingresados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cingresados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cingresados[10]++;
                }else{
                    $cingresados[11]++;
                }
            }

            foreach($detectados as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cdetectados[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cdetectados[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cdetectados[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cdetectados[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cdetectados[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cdetectados[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cdetectados[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cdetectados[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cdetectados[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cdetectados[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cdetectados[10]++;
                }else{
                    $cdetectados[11]++;
                }
            }

            $html = $this->renderView('SgvsBundle:Reportes:enfermedades-pdf.html.twig', array(
                'casos' => $casos,
                'tipo' => $enfermedad,
                'fecha'=>$fecha,
                'ejex'=>$meses,
                'chartTitle'=>'Casos de '.$enfermedad.' en los últimos 12 meses',
                'cingresados'=>$cingresados,
                'cdetectados'=>$cdetectados,
            ));

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="Informe de '.$enfermedad.' Detectados.pdf"'
                )
            );


        }
    }


    public function ingresosAction(){
        return $this->render('SgvsBundle:Reportes:ingresos-get.html.twig');
    }

    public function ingresoSelectedAction(Request $request)
    {

        $periodo = $request->get('periodo');
        $em = $this->getDoctrine()->getManager();
        if ($periodo == "diario") {
            $fecha = new \DateTime($request->get('tiempo'));
            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha = :fecha AND c.lugaringreso IS NOT null';
            $query = $em->createQuery($dql);
            $query->setParameter('fecha', $fecha);
            $casos = $query->getResult();

            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha BETWEEN :fecha1 AND :fecha AND c.lugaringreso IS NOT null';
            $fecha1=new \DateTime($request->get('tiempo'));
            $query = $em->createQuery($dql);
            $query->setParameter('fecha', $fecha);
            $query->setParameter('fecha1', $fecha1->sub(new \DateInterval('P30D')));
            $todos = $query->getResult();

            $cantidad=array();
            $ejeX=array();
            for($i=0;$i<30;$i++){
                $cantidad[$fecha->format('d-m-Y')]=0;
                $ejeX[$i]=$fecha->format('d-m-Y');
               $fecha=$fecha->sub(new \DateInterval('P1D'));
            }

            foreach($todos as $caso){
                $cantidad[$caso->getFecha()->format('d-m-Y')]++;
            }
            $cantidad=array_reverse($cantidad);
            $ejeX=array_reverse($ejeX);
            return $this->render('SgvsBundle:Reportes:ingresos-post.html.twig', array(
                'casos' => $casos,
                'fecha' =>$fecha->format('d-m-Y'),
                'fechastring'=>$fecha->format('d-m-Y'),
                'periodo'=>$periodo,
                'ejex'=>$ejeX,
                'chartTitle'=>'Pacientes ingresados en los últimos 30 días',
                'ingresados'=>$cantidad,


            ));
        }elseif($periodo == "mensual"){
            $mes = $request->get('mes');
            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE MONTH (c.fecha) = :mes AND c.lugaringreso IS NOT null ORDER BY c.fecha';
            $query = $em->createQuery($dql);
            $query->setParameter('mes', $mes);            ;
            $casos = $query->getResult();


            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null ORDER BY c.fecha';
            $query = $em->createQuery($dql);
            $todos = $query->getResult();


            $cantidad=array(0,0,0,0,0,0,0,0,0,0,0,0);
            $meses=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

            foreach($todos as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cantidad[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cantidad[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cantidad[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cantidad[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cantidad[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cantidad[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cantidad[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cantidad[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cantidad[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cantidad[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cantidad[10]++;
                }else{
                    $cantidad[11]++;
                }
            }

            return $this->render('SgvsBundle:Reportes:ingresos-post.html.twig', array(
                'casos' => $casos,
                'fecha' =>$mes,
                'fechastring'=>Util::mesString($mes).' '.date('Y'),
                'periodo'=>$periodo,
                'ejex'=>$meses,
                'chartTitle'=>'Pacientes ingresados por mese',
                'ingresados'=>$cantidad,


            ));
        }elseif($periodo=='semanal') {
            $semana=$request->get('semana');

            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE WEEK (c.fecha) = :semana AND c.lugaringreso IS NOT null ORDER BY c.fecha';
            $query = $em->createQuery($dql);
            $query->setParameter('semana', $semana);
            $casos = $query->getResult();

            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null';
            $query = $em->createQuery($dql);
            $todos = $query->getResult();

            $cantidad=array();
            $ejeX=array();
            for($i=1;$i<54;$i++){
                $cantidad[$i]=0;
                $ejeX[$i]=$i.'º';
            }

            foreach($todos as $caso){
                $cantidad[$caso->getFecha()->format('W')]++;
            }
            return $this->render('SgvsBundle:Reportes:ingresos-post.html.twig', array(
                'casos' => $casos,
                'fecha' =>$semana,
                'fechastring'=>'Semana '.$semana,
                'periodo'=>$periodo,
                'ejex'=>$ejeX,
                'chartTitle'=>'Pacientes ingresados por semanas estadísticas',
                'ingresados'=>$cantidad,


            ));

        }else{

            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE  c.lugaringreso IS NOT null ORDER BY c.fecha';
            $query = $em->createQuery($dql);

            $casos = $query->getResult();

            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null ORDER BY c.fecha';
            $query = $em->createQuery($dql);
            $todos = $query->getResult();


            $cantidad=array(0,0,0,0,0,0,0,0,0,0,0,0);
            $meses=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

            foreach($todos as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cantidad[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cantidad[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cantidad[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cantidad[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cantidad[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cantidad[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cantidad[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cantidad[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cantidad[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cantidad[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cantidad[10]++;
                }else{
                    $cantidad[11]++;
                }
            }

            return $this->render('SgvsBundle:Reportes:ingresos-post.html.twig', array(
                'casos' => $casos,
                'fecha' =>'',
                'fechastring'=>date(Y),
                'periodo'=>$periodo,
                'ejex'=>$meses,
                'chartTitle'=>'Pacientes ingresados por mes',
                'ingresados'=>$cantidad,


            ));
        }
    }


    public function ingresosPDFAction(Request $request){

        $periodo = $request->query->get('periodo');
        $em = $this->getDoctrine()->getManager();

        if ($periodo == "diario") {
            $fecha = new \DateTime($request->query->get('tiempo'));
            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha = :fecha AND c.lugaringreso IS NOT null';
            $query = $em->createQuery($dql);
            $query->setParameter('fecha', $fecha);
            $casos = $query->getResult();

            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha BETWEEN :fecha1 AND :fecha AND c.lugaringreso IS NOT null';
            $fecha1=new \DateTime($request->get('tiempo'));
            $query = $em->createQuery($dql);
            $query->setParameter('fecha', $fecha);
            $query->setParameter('fecha1', $fecha1->sub(new \DateInterval('P30D')));
            $todos = $query->getResult();

            $cantidad=array();
            $ejeX=array();
            for($i=0;$i<30;$i++){
                $cantidad[$fecha->format('d-m-Y')]=0;
                $ejeX[$i]=$fecha->format('d-m-Y');
                $fecha=$fecha->sub(new \DateInterval('P1D'));
            }

            foreach($todos as $caso){
                $cantidad[$caso->getFecha()->format('d-m-Y')]++;
            }
            $cantidad=array_reverse($cantidad);
            $ejeX=array_reverse($ejeX);

            $html = $this->renderView('SgvsBundle:Reportes:ingresos-pdf.html.twig', array(
                'casos' => $casos,
                'fecha'=>$fecha->format('d-m-Y'),
                'ingresados'=>$cantidad,
                'ejex'=>$ejeX,
                'chartTitle'=>'Pacientes ingresados últimos 30 días',

            ));


            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="Informe de ingresos '.$fecha->format('d-m-Y').'.pdf"'
                )
            );


        }elseif($periodo == "mensual"){
            $mes = $request->query->get('fecha');

            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE MONTH (c.fecha) = :mes AND c.lugaringreso IS NOT null ORDER BY c.fecha';
            $query = $em->createQuery($dql);
            $query->setParameter('mes', $mes);
            $casos = $query->getResult();

            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null ORDER BY c.fecha';
            $query = $em->createQuery($dql);
            $todos = $query->getResult();


            $cantidad=array(0,0,0,0,0,0,0,0,0,0,0,0);
            $meses=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

            foreach($todos as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cantidad[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cantidad[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cantidad[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cantidad[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cantidad[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cantidad[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cantidad[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cantidad[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cantidad[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cantidad[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cantidad[10]++;
                }else{
                    $cantidad[11]++;
                }
            }
            $html = $this->renderView('SgvsBundle:Reportes:ingresos-pdf.html.twig', array(
                'casos' => $casos,
                'fecha'=>Util::mesString($mes).' '.date('Y'),
                'ingresados'=>$cantidad,
                'ejex'=>$meses,
                'chartTitle'=>'Pacientes ingrsadosúltimos 12 meses',

            ));

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="Informe de ingresos '.$mes.' '.date('Y').'.pdf"'
                )
            );

        }elseif($periodo=='semanal') {
            $semana=$request->query->get('fecha');

            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE WEEK (c.fecha) = :semana AND c.lugaringreso IS NOT null ORDER BY c.fecha';
            $query = $em->createQuery($dql);
            $query->setParameter('semana', $semana);
            $casos = $query->getResult();

            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null';
            $query = $em->createQuery($dql);
            $todos = $query->getResult();

            $cantidad=array();
            $ejeX=array();
            for($i=1;$i<54;$i++){
                $cantidad[$i]=0;
                $ejeX[$i]=$i.'º';
            }

            foreach($todos as $caso){
                $cantidad[$caso->getFecha()->format('W')]++;
            }
            $html = $this->renderView('SgvsBundle:Reportes:ingresos-pdf.html.twig', array(
                'casos' => $casos,
                'fecha'=>'Semana '.$semana,
                'ingresados'=>$cantidad,
                'ejex'=>$ejeX,
                'chartTitle'=>'Pacientes ingresados por semanas estadísticas',


            ));


            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="Informe de ingresos Semana estadística '.$semana.'º.pdf"'
                )
            );
        }else{

            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE  c.lugaringreso IS NOT null ORDER BY c.fecha';
            $query = $em->createQuery($dql);

            $casos = $query->getResult();


            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.lugaringreso IS NOT null ORDER BY c.fecha';
            $query = $em->createQuery($dql);
            $todos = $query->getResult();


            $cantidad=array(0,0,0,0,0,0,0,0,0,0,0,0);
            $meses=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

            foreach($todos as $caso){
                if($caso->getFecha()->format('m')=='01'){
                    $cantidad[0]++;
                }elseif($caso->getFecha()->format('m')=='02'){
                    $cantidad[1]++;
                }elseif($caso->getFecha()->format('m')=='03'){
                    $cantidad[2]++;
                }elseif($caso->getFecha()->format('m')=='04'){
                    $cantidad[3]++;
                }elseif($caso->getFecha()->format('m')=='05'){
                    $cantidad[4]++;
                }elseif($caso->getFecha()->format('m')=='06'){
                    $cantidad[5]++;
                }elseif($caso->getFecha()->format('m')=='07'){
                    $cantidad[6]++;
                }elseif($caso->getFecha()->format('m')=='08'){
                    $cantidad[7]++;
                }elseif($caso->getFecha()->format('m')=='09'){
                    $cantidad[8]++;
                }elseif($caso->getFecha()->format('m')=='10'){
                    $cantidad[9]++;
                }elseif($caso->getFecha()->format('m')=='11'){
                    $cantidad[10]++;
                }else{
                    $cantidad[11]++;
                }
            }



            $html = $this->renderView('SgvsBundle:Reportes:ingresos-pdf.html.twig', array(
                'casos' => $casos,
                'fecha'=>'Año '.date('Y'),
                'ejex'=>$meses,
                'chartTitle'=>'Pacientes ingresados por mes',
                'ingresados'=>$cantidad,

            ));

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="Informe de ingresos Año '.date('Y').'.pdf"'
                )
            );
        }
    }

}