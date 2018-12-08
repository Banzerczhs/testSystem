<template>
    <el-aside class="user">
        <div class="user-avatar">
            <img :src="avatarSrc">
        </div>
        <p>姓名 : {{username}}</p>
        <p>考号 : {{uid}}</p>
    </el-aside>
</template>

<script>
export default {
    name : 'user',
    props : ['userinfo'],
    data(){
        return {
            username : '',
            uid : '',
            avatarSrc : ''
        }
    },
    watch : {
        "userinfo" : {
            handler(newValue){
                let {list}=newValue;

                this.username=list.name;
                this.uid=list.uid;
                this.avatarSrc=`http://localhost/testphp/public/image/${list.avatar}`;
            }
        }
    }
}
</script>

<style lang="less" scoped>
    .user{
        .user-avatar{
            width: 100%;
            img{
                display: block;
                width: 120px;
                height: 120px;
                margin: 120px auto;
                margin-bottom: 30px;
                border-radius: 50%;
                border: solid 6px rgba(64, 158, 255,0.5);
                box-shadow: 2px 2px 10px -2px rgba(58, 57, 57, 0.767);
            }
        }
        p{
            text-align: center;
            margin: 10px 0;
        }
    }
</style>
