export const taskDate = () => {
    let dateObject = new Date()
    let date = dateObject.getDate()
    let month = JSON.stringify(dateObject.getMonth() + 1)
    month = month.length === 1 ? '0'+ month : month
    let year = dateObject.getFullYear()
    return [date, month, year].join('-')
     
}