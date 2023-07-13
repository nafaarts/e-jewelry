const currencyFormatter = new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 2,
});

const convertToCurrency = (n) => {
    const removeChar = n.replace(/[^0-9\.]/g, "");
    const removeDot = removeChar.replace(/\./g, "");
    const formatedNumber = removeDot.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return formatedNumber;
};

export { currencyFormatter, convertToCurrency };
