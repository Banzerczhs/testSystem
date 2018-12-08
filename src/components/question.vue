<template>
    <el-main class="main">
        <div class="question">
            <p class="question-title">{{id}} : {{title}}</p>
            <div class="question-option">
                <div v-if="type=='radio'">
                    <p 
                        v-for="(item,index) in arr"
                        :key="index"
                        @click="recordsData(item,id)">
                        <el-radio
                            v-model="radio"
                            :label="item"
                            :style="{display:options[item]?'block':'none'}">
                            <span>{{item}} : </span>
                            <span>{{options[item]}}</span>
                        </el-radio>
                    </p>
                </div>
                <div v-else-if="type=='checkbox'" :key="id" @click="recordsData('',id)">
                    <el-checkbox-group v-model="checkList">
                        <el-checkbox
                            v-for="item in arr"
                            :key="options[item]"
                            :label="item">
                            <span>{{item}} : </span>
                            <span>{{options[item]}}</span>
                        </el-checkbox>
                    </el-checkbox-group>
                </div>
            </div>
            <div class="question-btn">
                <el-button type="primary" 
                    icon="el-icon-arrow-left" round 
                    :style="{'display':1==id?'none!important':'inline-block!important'}"
                    @click="prevQuestion">
                    上一题</el-button>
                <el-button type="primary" round 
                    :style="{'display':total==id?'none!important':'inline-block!important'}"
                    @click="nextQuestion">
                    下一题
                    <i class="el-icon-arrow-right el-icon--right"></i>
                </el-button>
                <div class="submit-data" :style="{'display':total==id?'inline-block!important':'none!important'}">
                    <el-button type="primary" round @click="submitData">提交</el-button>
                </div>
            </div>
        </div>
    </el-main>
</template>

<script>
export default {
    name : 'question',
    props : {
        'dataItem':{
            type : Object
        },
        'total':{
            type : Number
        }
    },
    watch : {
        dataItem : {
            handler(newValue){
                let {list,options}=newValue;
                this.title=this.escape2Html(list.timu);
                this.id=list.iNum;
                this.type=list.type;

                if(list.checked){
                    list.type=='radio'?this.radio=list.checked:this.checkList=[...list.checked];
                }else if(list.answer){
                    list.type=='radio'?this.radio=list.answer:this.checkList=[...list.answer];
                }else{
                    this.radio=null;
                    this.checkList=[];
                }
                
                this.arr=[];
                this.arr.push(...Object.keys(options));
                for(var attr in options){
                    options[attr]=this.escape2Html(options[attr]);
                }

                this.options=options;
            },
            deep : true
        }
    },
    data(){
        return {
            title : '',
            id : '',
            options : {},
            arr : [],
            radio : null,
            type : '',
            checkList : []
        }
    },
    methods : {
        escape2Html(str){       //对后台返回过来的数据进行转义字符处理
            var arrEntities={'lt':'<','gt':'>','nbsp':' ','amp':'&','quot':'"','ldquo':'"','rdquo':'"'};
            return str.replace(/&(lt|gt|nbsp|amp|quot|ldquo|rdquo);/ig,function(all,t){return arrEntities[t];});
        },
        prevQuestion(){         //切换至上一题
            this.$emit('prevquestion',-1)
        },
        nextQuestion(){         //切换至下一题
            this.$emit('nextquestion',1);
        },
        recordsData(val,id){       //收集题目的答案进行存储
            if(this.type=='radio'){
                this.radio=val;
            }
            setTimeout(() => {
                if(this.type!='radio'){
                    val=[...this.checkList];
                }
                this.$emit('recordsdata',{
                    value : val,
                    id
                });
            },200);
        },
        submitData(){       //最后提交数据
            this.$emit('submitdata');
        }
    }
}
</script>

<style lang="less" scoped>
    .main{
        padding: 0;
        margin: 10px;
        .question{
            width: 100%;
            height: 100%;
            position: relative;
            background-color: #fff;
            border-radius: 4px;
            padding: 20px;
            box-sizing: border-box;
            .question-option{
                width: 100%;
                label{
                    margin: 50px 0;
                    display: block;
                    span{
                        margin-right: 10px;
                    }
                }
            }
            .question-btn{
                position: absolute;
                right: 100px;
                bottom: 150px;
                .submit-data{
                    margin-left: 20px;
                }
            }
        }
    }
</style>


