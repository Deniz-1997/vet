export const mapInnerForm = ({ elevator_request, subject, elevator_info, elevator_site, ...rest }) => {
  return {
  ...rest,
    typeName: {
      label: elevator_request.name,
      value: elevator_request.code
    },
    approval_request_type: {
      label: elevator_request.name,
      value: elevator_request.code
    },
    subject: {
      ...subject,
      inn: subject.subject_inn,
      kpp: subject.subject_kpp,
      ogrn: subject.ogrn,
      address: {
        address_name: subject.address,
        additional_info: subject.address_add
      }
    },
    // ...subject,
    elevator_info_hazardous_object: elevator_info.elevator_info_hazardous_object.map(item => ({
      ...item
    })),
    
    elevator_info_insurance: elevator_info.elevator_info_insurance.map(item => ({
      ...item,
      doc_date: item.doc_date,
      doc_num: item.doc_num,
      document_type: {
        label: item.document_type.name,
        value: item.document_type.code
      },
      validity_date: item.validity_date
    })),
    elevator_info_mothballed_year: elevator_info.elevator_info_mothballed_year.map(item => ({
      ...item,
      year_val: item.year_val
    })),
    elevator_info_service: elevator_info.elevator_info_service.map(item => ({
      name: {
        label: item.name,
        value: item.code
      },
      addition: item.addition ? item.addition : null,
    })),
    elevator_info_processing: elevator_info.elevator_info_processing.map(item => ({
      name: {
        label: item.name,
        value: item.code
      }
    })),
    
    elevator_info_product: elevator_info.elevator_info_product.map(item => ({
      prod_type_id: item.prod_type_id,
      name_tnved: item.product_name,
      tnved: item.tnved,
      okpd2: item.okpd2,
      product: {
        label: item.okpd2_name,
        value: item.prod_type_id
      }
    })),
  is_locomotive: elevator_info.is_locomotive,
  is_locomotive_rent: elevator_info.is_locomotive_rent,
  is_testing_laboratory: elevator_info.is_testing_laboratory,
  loading_capacity_auto: elevator_info.loading_capacity_auto,
  loading_capacity_wagons: elevator_info.loading_capacity_wagons,
  loading_capacity_water: elevator_info.loading_capacity_water,
  railway_capacity_wagons: elevator_info.railway_capacity_wagons,
  railway_capacity_wagons_rent: elevator_info.railway_capacity_wagons_rent,
  
  loading_capacity_auto_tons: elevator_info.loading_capacity_auto_tons,
  loading_capacity_train_tons: elevator_info.loading_capacity_train_tons,
  railway_in: elevator_info.railway_in,
  railway_length: elevator_info.railway_length,
  railway_length_rent: elevator_info.railway_length_rent,
  railway_out: elevator_info.railway_out,
  station: elevator_info.station,
  water_in: elevator_info.water_in,
  water_out: elevator_info.water_out,
  elevator_info_id: elevator_info.elevator_info_id,
  auto_in: elevator_info.auto_in,
  auto_out: elevator_info.auto_out,
  capacity_mothballed_tons: elevator_info.capacity_mothballed_tons,
  capacity_tons: elevator_info.capacity_tons,
  elevator_site: elevator_site.map(item => ({
    ...item,
    address_name: item.address_name,
    cadastral_number: item.cadastral_number,
    capacity_tons: item.capacity_tons,
    act_commissioning_date: typeof item.act_commissioning_date === 'string' ?
      new Date(item.act_commissioning_date.replace(/(\d{2})\.(\d{2})\.(\d{4})/, '$3-$2-$1')) :
      item.act_commissioning_date,
    own_rent_doc_date: typeof item.own_rent_doc_date === 'string' ?
      new Date(item.own_rent_doc_date.replace(/(\d{2})\.(\d{2})\.(\d{4})/, '$3-$2-$1')) :
      item.own_rent_doc_date,
    own_rent: item.own_rent ? {
      name: item.own_rent.name,
      code: item.own_rent.code
    } : null,
    elevator_site_storage: item.elevator_site_storage ? {
      name: item.elevator_site_storage.name,
      code: item.elevator_site_storage.code
    } : null,
    granary_type: {
      name: item.granary_type.name,
      code: item.granary_type.id,
      ...item.granary_type,
    }
  })),
  }
};
