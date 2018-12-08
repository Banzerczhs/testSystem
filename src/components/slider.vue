<template>
    <el-aside class="side">
        <ul class="btn-list">
            <li><h2>题号:</h2></li>
            <li class="list-item" 
                v-for="(item,index) in total"
                :key="index"
                @click="toggleItem(item,$event)"
                :class="{active:isActive===index}">
                {{item}}
            </li>
        </ul>
        
    </el-aside>
</template>


<script>

export default {
    name : 'slider',
    props : {
        'num':{
            type : Number
        },
        'current':{
            type : Number
        }
    },
    data(){
        return {
            total : this.num,
            isActive : this.current
        }
    },
    watch : {
        num : {
            handler(newValue){
                this.total=this.num;
            }
        },
        current : {
            handler(newValue){
                this.isActive=newValue;
            }
        },
        deep : true
    },
    methods : {
        toggleItem(id,ev){
            this.$emit('getquestionid',id);
            this.isActive=id-1;
        }
    }
}
</script>

<style lang="less" scoped>
    .side{
        width: 22% !important;
        position: relative;
        h2{
            margin-bottom: 20px;
        }
        .btn-list{
            width: 100%;
            height: 100%;
            padding: 60px 30px;
            padding-bottom: 0;
            box-sizing: border-box;
            .list-item{
                &.active{
                    background-color: rgba(64, 158, 255,1);
                    color: #fff;
                    box-shadow: 0px 4px 10px -1px rgba(92, 80, 41, 0.8);
                }
                width: 50px;
                height: 50px;
                list-style: none;
                border-radius: 50%;
                background-color: rgba(245, 239, 191,0.5);
                color: rgb(54, 53, 53);
                float: left;
                margin: 10px;
                text-align: center;
                line-height: 51px;
                font-size: 14px;
                cursor: pointer;
                transition: .2s linear;
            }
            .list-item:hover{
                background-color: rgba(64, 158, 255,1);
                color: #fff;
            }
        }
        .submit-data{
            position: absolute;
            bottom: 16%;
            right: 10%;
        }
    }
</style>

