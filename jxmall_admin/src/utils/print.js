import { getLodop } from "@/utils/LodopFuncs";
export const printPdf = (html) => {
    let LODOP = getLodop();
    LODOP.SET_PRINT_STYLEA(0, "HtmWaitMilSecs", 1000);
 
    LODOP.PRINT_INIT("订货单");
    LODOP.SET_PRINT_STYLE("FontSize", 18);
    LODOP.SET_PRINT_STYLE("Bold", 1);
    LODOP.ADD_PRINT_HTM(
        "0",
        "0",
        "100%",
        "100%",
        html
    );
    LODOP.PRINT();
}