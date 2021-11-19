export default class Gate{

   constructor(user){
    this.user = user;
   }

   isAdmin(){
       return this.user.type === 'admin';
   }

   isEmployee(){
    return this.user.type === 'employee';
    }

    isAdminOrisEmployee(){
        if(this.user.type === 'admin' || this.user.type === 'employee'){
            return true;
        }

    }
    isclient(){
        return this.user.type === 'client';
    }



}
