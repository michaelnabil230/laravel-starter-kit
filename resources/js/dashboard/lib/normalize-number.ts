const translateNumericFormat = (value: string): string => {
    const arabic = '٠١٢٣٤٥٦٧٨٩';
    const english = '0123456789';

    return value
        .split('')
        .map((char) => {
            const index = arabic.indexOf(char);
            return index > -1 ? english[index] : char;
        })
        .join('');
};

export default translateNumericFormat;
