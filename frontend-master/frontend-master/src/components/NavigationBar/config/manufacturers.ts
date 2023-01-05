import { EAction } from "@/utils";
import { TMenuItem } from "../models";

export default {
  label: 'Сельскохозяйственные товаропроизводители и другие лица, осуществляющие деятельность в области развития зернового комплекса',
  pages: [
    {
      label: 'Реестр товаропроизводителей',
      path: '/manufacturers',
      enable: EAction.READ_MANUFACTURER_REGISTER,
    }
  ]
} as TMenuItem;
