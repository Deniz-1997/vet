const sdizSortMap = {
  'objects.lot.objects.okpd2.product_name_convert': 'objects.lot.objects.okpd2.name',
  'objects.lot.objects.target.name': 'objects.lot.objects.lot_target.name',
  'objects.lot.objects.purpose.name': 'objects.lot.objects.lot_purpose.name',
  'objects.lot.amount_kg_original_mask': 'objects.lot.amount_kg_original',
  'objects.owner.formattedName': 'objects.owner.name',
  status_translate: 'status_id',
};

const sdizGpbSortMap = {
  'objects.gpb.objects.okpd2.product_name_convert': 'objects.gpb.objects.okpd2.name',
  'objects.gpb.objects.target.name': 'objects.gpb.objects.lot_target.name',
  'objects.gpb.objects.purpose.name': 'objects.gpb.objects.lot_purpose.name',
  'objects.gpb.amount_kg_original_mask': 'objects.gpb.amount_kg_original',
  'objects.owner.formattedName': 'objects.owner.name',
  status_translate: 'status_id',
};

const SORT_MAP = {
  lot: {
    amount_kg_original_mask: 'amount_kg_original',
    amount_kg_mask: 'amount_kg',
    'objects.target.name': 'objects.lot_target.name',
    'objects.okpd2.product_name_convert': 'objects.okpd2.name',
    'objects.purpose.name': 'objects.lot_purpose.name',
  },
  lot_ppz: {
    amount_kg_original_mask: 'amount_kg_original',
    amount_kg_mask: 'amount_kg',
    'objects.okpd2.product_name_convert': 'objects.okpd2.name',
    'objects.target.name': 'objects.lot_target.name',
    'objects.purpose.name': 'objects.lot_purpose.name',
  },
  gosImplementation: {
    'place_of_cultivation.address': 'objects.place_of_cultivation.address',
    'current_location.address': 'objects.current_location.address',
    'okpd2.product_name_convert': 'objects.okpd2.name',
    'ownership_details.name': 'objects.ownership_details.name',
    'lot_target.name': 'objects.lot_target.name',
    status_name: 'status_id',
    numbersFromSubjectFormatted: 'objects.lots_numbers_from_subject.lots_numbers_from_subject',
  },
  gpb_out: {
    'current_location.address': 'objects.current_location.address',
    'manufacturer.name': 'objects.manufacturer.name',
  },
  sdiz: sdizSortMap,
  sdizShort: sdizSortMap,
  sdiz_gpb: sdizGpbSortMap,
  sdizShortGpb: sdizGpbSortMap,
  sdiz_agent_list: {
    'sdiz.sdiz_number': 'objects.sdiz.sdiz_number',
    'repository.name': 'objects.repository.name',
    'owner.name': 'objects.owner.name',
    'operator.full_name': 'objects.operator.last_name',
    'okpd2.product_name_convert': 'objects.okpd2.name',
    'sdiz.contract_date': 'objects.sdiz.contract_date',
    'sdiz.contract_number': 'objects.sdiz.contract_number',
  },
  sdiz_elevator: {
    'objects.lot.objects.okpd2.product_name_convert': 'objects.lot.objects.okpd2.name',
    'objects.lot.objects.target.name': 'objects.lot.objects.lot_target.name',
    'objects.lot.objects.purpose.name': 'objects.lot.objects.lot_purpose.name',
    'objects.lot.amount_kg_original_mask': 'objects.lot.amount_kg_original',
    status_translate: 'status_id',
  },

  lot_elevator: {
    amount_kg_original_mask: 'amount_kg_original',
    amount_kg_mask: 'amount_kg',
    'objects.okpd2.product_name_convert': 'objects.okpd2.name',
    'objects.target.name': 'objects.lot_target.name',
    'objects.purpose.name': 'objects.lot_purpose.name',
  },

  gosSubmitedByManufacturers: {
    'owner.name': 'objects.owner.name',
    'place_of_cultivation.address': 'objects.place_of_cultivation.address',
    'current_location.address': 'objects.current_location.address',
    'okpd2.product_name_convert': 'objects.okpd2.name',
    'ownership_details.name': 'objects.ownership_details.name',
    'lot_target.name': 'objects.lot_target.name',
    numbersFromSubjectFormatted: 'objects.lots_numbers_from_subject.lots_numbers_from_subject',
  },

  reservesSdiz: {
    'operator.full_name': 'objects.operator.last_name',
  },

  reservesLot: {
    'okpd2.product_name_convert': 'objects.okpd2.name',
    owner_short_name: 'objects.owner.short_name',
    'operator.full_name': 'objects.operator.last_name',
  },

  reservesGpb: {
    'operator.full_name': 'objects.operator.last_name',
    'okpd2.product_name_convert': 'objects.okpd2.name',
    owner_short_name: 'objects.owner.short_name',
  },

  gosResearch: {
    'place_of_checking.address': 'objects.place_of_checking.address',
    'okpd2.product_name_convert': 'objects.okpd2.name',
    'lot_target.name': 'objects.lot_target.name',
    'owner.name': 'objects.owner.name',
    amount_kg_original_mask: 'amount_kg_original',
    amount_kg_available_mask: 'amount_kg_available',
    locationOfCreationFormatted: 'objects.lots_numbers_from_subject.lots_numbers_from_subject',
  },

  conductedGosResearch: {
    'place_of_checking.address': 'objects.place_of_checking.address',
    'okpd2.product_name_convert': 'objects.okpd2.name',
    'lot_target.name': 'objects.lot_target.name',
    status_name: 'status_id',
    amount_kg_original_mask: 'amount_kg_original',
    amount_kg_available_mask: 'amount_kg_available',
    locationOfCreationFormatted: 'objects.lots_numbers_from_subject.lots_numbers_from_subject',
  },
  withdrawal: {
    amount_kg_mask: 'amount_kg',
    status_translate: 'status_id',
    'okpd2.product_name_convert': 'objects.okpd2.name',
    'owner.name': 'objects.owner.name',
    'current_check_location.address': 'objects.current_check_location.address',
  },
  prescription: {
    status_translate: 'status_id',
    'legal_operator.name': 'objects.legal_operator.name',
    'operator.full_name': 'objects.operator.second_name',
    restrictions_text_convert: 'restrictions_text',
    restrictions_bin_convert: 'restrictions_bin',
  },
  expertise: {
    status_translate: 'status_id',
    expertise_type_translate: 'expertise_type',
    gw_id: 'objects.withdrawal.gw_number',
  },
};

export default SORT_MAP;
