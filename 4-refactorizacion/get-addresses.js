export default function useAddresses() {
  const user = {
    name: "John Doe",
    addresses: [
      {
        id: 1,
        name: "Home",
        address: "Calle de la piruleta, 1",
        city: "Madrid",
        zipCode: "28001",
      },
      {
        id: 2,
        name: "Work",
        address: "Calle de la piruleta, 2",
        city: "Madrid",
        zipCode: "28001",
      },
    ],
  };

  /** CODE TO REFACTOR **/
  let addresses = ""; // Declare addresses variable
  user.addresses.forEach((address) => {
    // Loop through addresses
    addresses += address.address + ", "; // Concatenate address to addresses variable
  });
  addresses = addresses.slice(0, -2); // Remove last comma and space from addresses variable

  /** REFACTORED CODE **/
}
