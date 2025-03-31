import { createColumn } from "./createColumn.js";
import { getGridNodeTypes } from "./getGridNodeTypes.js";

export function createGrid(schema, colsCount, stackAt, asymmetric, leftSpan = null, rightSpan = null, colContent = null) {
  const { grid, column } = getGridNodeTypes(schema);
  const cols = [];
  let type = asymmetric === false ? 'symmetric' : 'asymmetric';

  if (asymmetric) {
    cols.push(createColumn(column, leftSpan, colContent));
    cols.push(createColumn(column, rightSpan, colContent));
  } else {
    for (let index = 0; index < colsCount; index += 1) {
      const col = createColumn(column, 1, colContent);

      if (col) {
        cols.push(col);
      }
    }
  }

  return grid.createChecked({
      'data-columns': colsCount,
      'data-type': type,
      'data-stack-at': stackAt
  }, cols);
}
