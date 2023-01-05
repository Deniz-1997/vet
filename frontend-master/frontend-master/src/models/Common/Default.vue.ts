export interface AvailableFilters {
    name?: string;
    operator?: string;
    key: string;
    type?: string;
    value?: any;
    child?: any;
}

export interface FieldFilter {
    field: string;
    operator: string;
    value: any;
}

export type HeaderSdizItem = {
    text: string,
    value: string,
    width?: string,
    align?: string,
    notExclude?: boolean | undefined
};

export interface DefaultVueInterface {
    id: number | null;
    owner_id?: number | null;
    operator_id?: number | null;
    current_location_id?: number | string | null;
    date_enter?: string | null;
    available_filters: AvailableFilters[];

    lot_tables_paper_store_title?: string;
    name_from_another_batch?: string;
    name_from_imported?: string;
    name_from_residues?: string;
    name_from_sdiz?: string;

    create_from_another_batch?: string;
    create_from_field?: string;
    create_from_imported?: string;
    create_from_residues?: string;
    create_from_sdiz?: string;
    create_from_in_product?: string;

    name_route_list?: string;
    name_route_create?: string;
    name_route_detail?: string;

    show_apiendpoit?: string;
    list_apiendpoit?: string;
    create_apiendpoit?: string;
    update_apiendpoit?: string;
    delete_apiendpoit?: string;
}
