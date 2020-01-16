export default class Gate{

    constructor(user){
        this.user = user;
    }

    isAdmin(){
        return this.user.type === 'admin';
    }

    isUser(){
        return this.user.type === 'user';
    }

    isManager(){
        return this.user.type === 'manager';
    }
    isAdminOrManager(){
        if(this.user.type === 'admin' || this.user.type === 'manager'){
            return true;
        }

    }


}
