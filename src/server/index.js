import Axios from "axios";

let http=Axios.create({
    baseURL : 'http://localhost/',
    timeout : 5000,
    withCredentials : true,
    headers : {
        'X-Requested-With' : 'XMLHttpRequest',
        'Cache-control' : 'no-cache',
        'content-type' : 'application/x-www-form-urlencoded'
    },
    transformResponse(data){
        return data;
    }
})


export const getData = () => http.post('/testphp/gettestdata.php');
export const logining = (id,passwd) => http.post('/testphp/verification.php',`userid=${id}&password=${passwd}`);
export const saveData = (userinfo,quesinfo,time) => http.post('/testphp/savequesinfo.php',{
    uid: userinfo.uid,
    info : quesinfo,
    time
},{
    headers : {'content-type':'application/json'}
});