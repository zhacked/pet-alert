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

    isclient(){
        return this.user.type === 'client';
        }



}
