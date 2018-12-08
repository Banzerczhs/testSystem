<?php
    header('Access-Control-Allow-Origin:http://localhost:8080');
    header('Access-Control-Allow-Methods:POST');
    header('Access-Control-Allow-Headers:x-requested-with,content-type,Cache-control');
    header('content-type:text/html;charset="utf-8"');
    header('Access-Control-Allow-Credentials:true');

    $servername='localhost';
    $username='Banzer';
    $password='drVtpnEte5rdSWvu';
    $dbname='db_chengji';

    $conn=new mysqli($servername,$username,$password,$dbname);

    if($conn->connect_error){
        echo '数据库连接失败';
    }else{
        global $conn;
        $data=json_decode(file_get_contents("php://input"),true);

        $uid=$data['uid'];
        $uid=pack("H*",$uid);       //对16进制加密的数据进行解密
        $info=$data['info'];
        $jsoninfo=json_encode($data['info']);
        $saveSql='';
        $fraction=reduceNum($info);
        date_default_timezone_set('PRC');       //设置时区否则会报警告  PRC为中国时区
        $time=date("Y-m-d h:i:s",time());
        if($data['time']){
            $saveSql="UPDATE tb_kaosheng SET info='$jsoninfo',zongfen='$fraction',jiaojuanshijian='$time' WHERE kaohao='$uid'";
        }else{
            $saveSql="UPDATE tb_kaosheng SET info='$jsoninfo' WHERE kaohao='$uid'";
        }
        
        $conn->query($saveSql); //向数据库插入数据

        echo json_encode(array('msg'=>"交卷成功，你的分数为$fraction,即将退出","status"=>1));
    }

    function reduceNum($data){
        global $conn;
        $total=0;
        for($i=0;$i<count($data);$i++){
            $id=$data[$i]['id'];
            $getInfo="SELECT * FROM tb_shiti WHERE id=$id";
            $result=$conn->query($getInfo);

            if($result->num_rows>0){
                $row=$result->fetch_assoc();
                if(is_array($data[$i]['answer'])){
                    $data[$i]['answer']=implode("",$data[$i]['answer']);
                }
                
                if($row['biaoda']==$data[$i]['answer']){
                    $total+=$row['fenzhi'];
                }
            }
        }

        return $total;
    }
?>