<template>
    <div class="login-wrap">
        <el-input
            placeholder="请输入考号"
            suffix-icon="el-icon-info"
            v-model="userid">
        </el-input>
        <el-input
            :type="type"
            placeholder="请输入密码"
            v-model="passwd">
            <i slot="suffix" class="el-input__icon el-icon-view" @click.stop.prevent="clickFn"></i>
        </el-input>
        <el-button type="primary" @click="login">登录</el-button>
    </div>
</template>

<script>
import {logining} from "@/server/index";

export default {
    name : 'logo',
    data(){
        return {
            userid : '',
            passwd : '',
            type : 'password'
        }
    },
    methods : {
        clickFn(ev){
            let {path}=ev;
            let currentEl=null;
            path.some(item=>{
                if(item.classList.contains('el-input')){
                    currentEl=item;
                    return item;
                }
            });

            if(this.passwd){
                this.type=this.type==='password'?'text':'password';
            }
        },
        async login(){
            let {userid,passwd}=this;

            if(!userid&&!passwd){
                this.$message({
                    message: '请输入正确的信息',
                    type: 'error'
                });

                return;
            }
            let {data}=await logining(userid,passwd);
            let msg=JSON.parse(data);
            if(msg.status!=1){
                this.$message({
                    message : msg.msg,
                    type : 'warning'
                })
            }else{
                this.$message({
                    message : msg.msg,
                    type : 'success'
                })

                this.$router.replace('/');
            }
        }
    }
}
</script>

<style lang="less" scoped>
    .login-wrap{
        position: absolute;
        left: 50%;
        top:20%;
        transform: translateX(-50%);
        text-align: center;
        .el-input{
            display: block;
            width: 260px;
            margin: 5px 0;
            border:none;
            height: 40px;
            overflow: hidden;
            border-radius: 4px;
            margin: 10px 0;
            .el-icon-view{
                cursor: pointer;
            }
        }
        .el-button{
            margin-top: 10px;
        }
    }
</style>


