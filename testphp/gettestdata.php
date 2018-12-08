<?php
    header('Access-Control-Allow-Origin:http://localhost:8080');
    header('Access-Control-Allow-Methods:POST');
    header('Access-Control-Allow-Headers:x-requested-with,content-type,Cache-control');
    header('content-type:text/html;charset="utf-8"');
    header('Access-Control-Allow-Credentials:true');

    //创建mysqli连接数据库

    $servername='localhost';
    $username="Banzer";
    $password="drVtpnEte5rdSWvu";
    $dbname='db_chengji';
    $connect=new mysqli($servername,$username,$password,$dbname);

    if($connect->connect_error){
        echo '数据库连接失败:'.$connect->connect_error;
    }else{
        getQuestions();
    }

    //获取试题
    function getQuestions(){
        global $connect;

        $userQuesinfo='';
        if(count($_COOKIE)){        //判断是否已经交过卷子
            $name=pack("H*",$_COOKIE['username']);

            $ver="SELECT * FROM tb_kaosheng WHERE kaohao='$name'";
            $tmp=$connect->query($ver)->fetch_assoc();
            
            $userQuesinfo=json_decode($tmp['info'],true);
            if($tmp['jiaojuanshijian']!='0000-00-00 00:00:00'){
                echo json_encode(array('msg'=>'你已经交卷无法继续答题','status'=>7));
                exit();
            }
        }

        $sql="SELECT * FROM tb_shiti";

        $result=$connect->query($sql);
        $rowNum=$result->num_rows;

        if(!$userQuesinfo){
            getRandQues($connect,$result);      //随机获取题目信息
        }else{
            getUserQues($connect,$userQuesinfo);
        }
    }

    //获取指定用户题目
    function getUserQues($conn,$arr){
        $resultArr=array();
        for($i=0;$i<count($arr);$i++){
            $id=$arr[$i]['id'];
            $sql="SELECT * FROM tb_shiti WHERE id='$id'";
            $row=$conn->query($sql)->fetch_assoc();
            if(mb_strlen($row['biaoda'],"utf-8")>1){        //判断是不是多选题
                $row['type']='checkbox';
            }else{
                $row['type']='radio';
            }
            $row['uid']=pack("H*",$_COOKIE['username']);
            $uid=$row['uid'];
            $usersql="SELECT * FROM tb_kaosheng WHERE kaohao='$uid'";
            $row['name']=$conn->query($usersql)->fetch_assoc()['xingming'];
            $row['avatar']=$conn->query($usersql)->fetch_assoc()['photos'];
            $row['answer']=$arr[$i]['answer'];
            unset($row['biaoda']);
            array_push($resultArr,$row);
        }

        echo json_encode($resultArr);
    }

    //随机获取题目信息
    function getRandQues($conn,$result){
        $arr=array();
        while($row=$result->fetch_assoc()){
            if(mb_strlen($row['biaoda'],"utf-8")>1){        //判断是不是多选题
                $row['type']='checkbox';
            }else{
                $row['type']='radio';
            }
            unset($row['biaoda']);
            $row['uid']=pack("H*",$_COOKIE['username']);
            $uid=$row['uid'];
            
            $usersql="SELECT * FROM tb_kaosheng WHERE kaohao='$uid'";
            $row['name']=$conn->query($usersql)->fetch_assoc()['xingming'];
            $row['avatar']=$conn->query($usersql)->fetch_assoc()['photos'];
            array_push($arr,$row);
        }
        
        $limit=20;
        $resultArr=array();
        $randArr=unique_rand(array(),$limit,count($arr));
        for($i=0;$i<$limit;$i=count($resultArr)){
            array_push($resultArr,$arr[$randArr[$i]]);
        }

        echo json_encode($resultArr);
    }

    //获取一组随机不重复的值
    function unique_rand($arr,$max,$total){
        if(count($arr)>=$max){
            return $arr;
        }

        for($i=count($arr);$i<$max;$i++){
            array_push($arr,mt_rand(0,$total-1));
        }

        $temp=array_flip($arr);
        $arr=array_keys($temp);

        return unique_rand($arr,$max,$total);
    }
?>