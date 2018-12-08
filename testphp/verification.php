<?php
    header('Access-Control-Allow-Origin:http://localhost:8080');
    header('content-type:text/html;charset="utf-8"');
    header('Access-Control-Allow-Headers:x-requested-with,content-type,Cache-control');
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
        $uid=$_POST['userid'];
        $passwd=$_POST['password'];
        $name=null;
        $code=null;
        
        if(count($_COOKIE)){
            $name=pack("H*",$_COOKIE['username']);
            $code=pack("H*",$_COOKIE['password']);
        }

        if($name==$uid&&$passwd==$code){
            $ver="SELECT jiaojuanshijian FROM tb_kaosheng WHERE kaohao=$name";
            $tmp=$conn->query($ver)->fetch_assoc();

            if($tmp['jiaojuanshijian']!='0000-00-00 00:00:00'){
                echo json_encode(array('msg'=>'你已经交卷无法继续答题','status'=>7));
                exit();
            }
        }

        $sql="SELECT kaohao,mima FROM tb_kaosheng WHERE kaohao='$uid' and mima='$passwd'";
        $result=$conn->query($sql);
        
        if($result->num_rows>0){
            echo json_encode(array('msg'=>'登录成功','status'=>1));
            
            setcookie('username',bin2hex($uid),time()+3600*24,'/');
            setcookie('password',bin2hex($passwd),time()+3600*24,'/');
        }else{
            $kaohao="SELECT kaohao FROM tb_kaosheng WHERE kaohao='$uid'";
            $pass="SELECT mima FROM tb_kaosheng WHERE mima='$passwd'";

            if(!$conn->query($kaohao)->num_rows&&!$conn->query($pass)->num_rows){
                echo json_encode(array('msg'=>'您输入的信息有误','status'=>4));
                exit();
            }

            if(!$conn->query($kaohao)->num_rows){
                echo json_encode(array('msg'=>'考号不存在','status'=>2));
                exit();
            }

            if(!$conn->query($pass)->num_rows){
                echo json_encode(array('msg'=>'密码错误','status'=>0));
                exit();
            }

            echo json_encode(array('msg'=>'密码错误','status'=>0));
        }
    }
?>