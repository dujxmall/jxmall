import {
	export_json_to_excel
} from '@/utils/Export2Excel.js'

export default function exportExcel(header, fields, data, name) {
	data = data.map(v => fields.map(j => v[j]))
 	export_json_to_excel(header, data, name);
}


//export default exportExcel;
