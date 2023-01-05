export function constructByInterface<T>(o: T, thisRef: any, types: { [key: string]: new(...args: any[]) => any } = {}, strongStrct: boolean = false): void {
    if (o) {
        Object.keys(o).forEach(key => {
            if (types[key]) {
                if (o[key] instanceof Array) {
                    thisRef[key] = o[key].map(item => new types[key](item));
                } else {
                    if (o[key]) {
                        thisRef[key] = new types[key](o[key]);
                    }
                }
            } else {
                if(typeof thisRef[key] === 'function'){
                    const m = thisRef[key];
                    thisRef[key] = new m(o[key]);
                } else {
                    if(!strongStrct){
                        thisRef[key] = o[key];
                    } else {
                        if(thisRef[key] !== undefined){
                            thisRef[key] = o[key];
                        }
                    }
                }
            }
        });
    }
}
