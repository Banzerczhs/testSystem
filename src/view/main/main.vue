<template>
    <el-container direction="vertical" class="container">
        <Header></Header>
        <div class="wrap">
            <Slider 
                :num="list.length" 
                :current="currentId"
                @getquestionid="updateQuestion">
            </Slider>
            <Question
                :data-item="getQuestionsData"
                :total="list.length"
                @prevquestion="toogleItem"
                @nextquestion="toogleItem"
                @recordsdata="getSelectVal"
                @submitdata="uploadData">
            </Question>
            <User :userinfo="getQuestionsData"></User>
        </div>
    </el-container>
</template>

<script>
import Header from "@/components/header";       //头部组件
import Slider from "@/components/slider";       //题目按钮组件
import User from "@/components/user";           //用户信息组件
import Question from "@/components/question";   //题目信息组件
import {getData,saveData} from "@/server/index";    //axios配置

export default {
    name : 'Home',
    data(){
        return {
            list : [],      //存储所有题目数据
            options : [],   //存储所有选项数据
            currentId : 0   //当前题目的id
        }
    },
    async created(){        //初始化获取数据
        let {data}=await getData();
        let cookie=document.cookie.split(';');
    
        
        if(cookie[0]){
            data=JSON.parse(data);
            if(data.status){
                this.$message({
                    message : '一边凉快去[○･｀Д´･ ○]',
                    type : 'warning'
                })

                setTimeout(()=>{
                    this.$router.replace('/login');
                },500);
                
                return ;
            }

            data=data.map((item,index)=>{
                item.iNum=index+1;
                item.checked=null;
                return item;
            });

            this.list.push(...data);

            this.renderQuestion();
        }else{
            this.$router.replace('/login');
        }

        window.addEventListener('beforeunload',this.interrutp);     //绑定浏览器窗口离开事件
    },
    beforeDestroy(){
        window.removeEventListener('beforeunload',this.interrutp);      //在组件注销时解绑浏览器窗口事件
    },
    methods : {
        renderQuestion(){       //处理题目数据
            let {list}=this;
            let options=[];
            let quesInfo=[];
            let userInfo={
                uid : document.cookie.split(';')[0].split('=')[1]
            };
            list.forEach(item=>{
                options.push({A:item.A,B:item.B,C:item.C,D:item.D,E:item.E});
                quesInfo.push({id : item.id,answer : item.checked});
            })

            saveData(userInfo,quesInfo,null);
            this.options.push(...options);
        },
        updateQuestion(iNum){   //切换题目数据
            this.currentId=iNum-1;
        },
        toogleItem(sign){       //切换题目的显示
            this.currentId+=sign;
        },
        getSelectVal(obj){      //获取当前选择的答案
            let {list}=this;
            list=list.map(item=>{
                if(item.iNum===obj.id){
                    item.checked=obj.value?obj.value:item.answer;
                }

                return item;
            })

            this.list=[...list];
        },
        async uploadData(){     //最后的数据上传
            let {list}=this;

            let quesInfo=[];
            let userInfo={
                uid : document.cookie.split(';')[0].split('=')[1]
            };

            let onff=true;
            list.forEach(item=>{
                item.checked=item.checked?item.checked:item.answer?item.answer:null;
                if(!item.checked){
                    onff=false;
                }
                quesInfo.push({id : item.id,answer : item.checked});
            })

            if(!onff){
                this.$confirm('您的题目还没有写完哦,提交过后将无法更改, 是否继续提交?', '提示', {
                    confirmButtonText: '提交',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.showTipmsg(quesInfo,userInfo);
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消提交'
                    });
                });
            }else{
                this.showTipmsg(quesInfo,userInfo);
            }
        },
        async showTipmsg(quesInfo,userInfo){
            let {data}=await saveData(userInfo,quesInfo,true);   //上传数据

            let {msg,status}=JSON.parse(data);
            if(status==1){
                this.$message({
                    message : msg,
                    type : 'success'
                })

                setTimeout(()=>{
                    this.$router.push('/login');
                },500);
            }
        },
        interrutp(ev){      //当窗口关闭时进行数据的记录
            let {list}=this;
            ev=ev||window.event;
            let quesInfo=[];
            let userInfo={
                uid : document.cookie.split(';')[0].split('=')[1]
            };
            list.forEach(item=>{
                item.checked=item.checked?item.checked:item.answer?item.answer:null;
                quesInfo.push({id : item.id,answer : item.checked});
            })

            saveData(userInfo,quesInfo,null);
            ev.returnValue='你确定要离开本页面吗';
        }
    },
    computed : {
        getQuestionsData(){
            return {list : this.list[this.currentId],options : this.options[this.currentId]};
        }
    },
    components : {
        Question,
        Header,
        Slider,
        User
    }
}
</script>

<style lang="less" scoped>
    .container{
        height: 100%;
        .wrap{
            display: flex;
            height: 100%;
            aside{
                max-height: 100%;
                background-color: #fff;
                margin: 10px 0;
                box-sizing: border-box;
                border-radius: 4px;
                box-shadow: 0 1px 20px 5px rgba(199, 197, 178, 0.5);
            }
        }
    }
</style>