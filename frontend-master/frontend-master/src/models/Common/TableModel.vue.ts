import {constructByInterface} from "@/utils/construct-by-interface";

export interface HeaderVueInterface {
    name: string;
    hide: boolean;
}

export interface FormElementInterface {
    type: string;
    placeholder: string;
    format?: string;
}

export interface LinkInterface {
    route: string;
}

export interface RowTableVueInterface {
    type: string;
    value: string;
    width?: string;
    disabled?: boolean;
    hideOnDetail?: boolean;
    methods?: MethodsForTableRow;
    data?: SelectRowTableVueModel | InputRowTableVueModel | DatepickerRowTableVueModel | LinkRowTableVueModel | null | undefined;
}

export class HeaderVueModel implements HeaderVueInterface {
    name: string = '';
    hide: boolean = false;

    constructor(o?) {
        constructByInterface(o, this);
    }
}

export class SelectRowTableVueModel implements FormElementInterface {
    type: string = '';
    placeholder: string = '';

    constructor(o?) {
        constructByInterface(o, this, );
    }
}

export class InputRowTableVueModel implements FormElementInterface {
    type: string = '';
    placeholder: string = '';

    constructor(o?) {
        constructByInterface(o, this, );
    }
}

export class DatepickerRowTableVueModel implements FormElementInterface {
    type: string = '';
    placeholder: string = '';
    format: string = 'DD.MM.YYYY h:mm';

    constructor(o?) {
        constructByInterface(o, this);
    }
}

export class LinkRowTableVueModel implements LinkInterface {
    route: string = '';

    constructor(o?) {
        constructByInterface(o, this);
    }
}

export interface MethodsForTableRow{
    onBlur: undefined | object | Function;
    onFocus: undefined | object | Function;
    placeholder: undefined | object | Function;
    onInput: undefined | object | Function;
    showError: undefined | object | Function;
}

export class RowTableVueModel implements RowTableVueInterface {
    type: string = '';
    value: string = '';
    width: string = '';
    minWidth: string = '';
    maxWidth: string = '';
    disabled: boolean = false;
    hideOnDetail: boolean = false;
    data?: SelectRowTableVueModel | InputRowTableVueModel | DatepickerRowTableVueModel | LinkRowTableVueModel | null | undefined= null;
    methods: MethodsForTableRow = {
        onBlur: undefined,
        placeholder: undefined,
        onFocus: undefined,
        onInput: undefined,
        showError: undefined,
    }

    constructor(o?) {
        switch (o.type) {
            case 'input':
                this.data = new InputRowTableVueModel(o);
                break;

            case 'link':
                this.data = new LinkRowTableVueModel(o);
                break;

            case 'datepicker':
                this.data = new DatepickerRowTableVueModel(o);
                break;

            case 'select':
                this.data = new SelectRowTableVueModel(o);
                break;
        }
        constructByInterface(o, this);

    }
}

